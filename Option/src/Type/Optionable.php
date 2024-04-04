<?php

namespace Gaiproject\Option\Type;

use Webkul\Product\Type\AbstractType;
use Illuminate\Support\Facades\Log;
use Gaiproject\Option\Repositories\ProductOptionValueRepository;
use Gaiproject\Option\Contracts\ProductOptionValue;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductVideoRepository;
use Webkul\Product\Repositories\ProductCustomerGroupPriceRepository;
use Gaiproject\Option\Helpers\Indexers\Price\Optionable as SimpleIndexer;

class Optionable extends AbstractType
{
    /**
     * Create a new product type instance.
     *
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Product\Repositories\ProductAttributeValueRepository  $attributeValueRepository
     * @param  \Webkul\Product\Repositories\ProductInventoryRepository  $productInventoryRepository
     * @param  \Webkul\Product\Repositories\ProductImageRepository  $productImageRepository
     * @param  \Webkul\Product\Repositories\ProductCustomerGroupPriceRepository  $productCustomerGroupPriceRepository
     * @param  \Webkul\Product\Repositories\ProductDownloadableLinkRepository  $productDownloadableLinkRepository
     * @param  \Webkul\Product\Repositories\ProductDownloadableSampleRepository  $productDownloadableSampleRepository
     * @param  \Webkul\Product\Repositories\ProductVideoRepository  $productVideoRepository
     * @param  \Gaiproject\Option\Repositories\ProductOptionValueRepository $productOptionValueRepository
     * @return void
     */
    public function __construct(
        CustomerRepository $customerRepository,
        AttributeRepository $attributeRepository,
        ProductRepository $productRepository,
        ProductAttributeValueRepository $attributeValueRepository,
        ProductInventoryRepository $productInventoryRepository,
        productImageRepository $productImageRepository,
        ProductVideoRepository $productVideoRepository,
        ProductCustomerGroupPriceRepository $productCustomerGroupPriceRepository,
        protected ProductOptionValueRepository $productOptionValueRepository,
    ) {
        parent::__construct(
            $customerRepository,
            $attributeRepository,
            $productRepository,
            $attributeValueRepository,
            $productInventoryRepository,
            $productImageRepository,
            $productVideoRepository,
            $productCustomerGroupPriceRepository
        );
    }

    /**
     * Create configurable product.
     *
     * @param  array  $data
     * @return \Webkul\Product\Contracts\Product
     */
    public function create(array $data)
    {

        $product = $this->productRepository->getModel()->create($data);
        $groups = $this->productOptionValueRepository->getFamilyOptions($product);

        $optionValues = $groups->flatMap(
            fn ($group) => array_map(
                fn ($option) => array_merge(
                    [
                        'required' => 0,
                        'value' => in_array($option['type'], ['select']) ? json_encode([]) : json_encode(new \stdClass()),
                    ],
                    [
                        'product_id' => $product->id,
                        'option_id' => $option['id']
                    ]
                ),
                $group['custom_options']->toArray()
            )
        )->toArray();

        $this->productOptionValueRepository->insert($optionValues);
        return $product;
    }

    /**
     * Update product options.
     *
     * @param  array  $data
     * @return void
     */
    public function updateOptions(array $data)
    {
        foreach ($data as $option) {
            $this->productOptionValueRepository->updateOrCreate([
                'product_id' => $option['product_id'],
                'option_id' => $option['option_id']
            ], $option);
        }
    }

     /**
     * Add product. Returns error message if can't prepare product.
     *
     * @param  array  $data
     * @return array
     */
    public function prepareForCart($data)
    {
        $data['quantity'] = $this->handleQuantity((int) $data['quantity']);

        $data = $this->getQtyRequest($data);

        if (! $this->haveSufficientQuantity($data['quantity'])) {
            return trans('shop::app.checkout.cart.inventory-warning');
        }

        $price = $this->getFinalPrice() + $this->getPriceIncrement($data['options']);

        $products = [
            [
                'product_id'        => $this->product->id,
                'sku'               => $this->product->sku,
                'quantity'          => $data['quantity'],
                'name'              => $this->product->name,
                'price'             => $convertedPrice = core()->convertPrice($price),
                'base_price'        => $price,
                'total'             => $convertedPrice * $data['quantity'],
                'base_total'        => $price * $data['quantity'],
                'weight'            => $this->product->weight ?? 0,
                'total_weight'      => ($this->product->weight ?? 0) * $data['quantity'],
                'base_total_weight' => ($this->product->weight ?? 0) * $data['quantity'],
                'type'              => $this->product->type,
                'additional'        => $this->getAdditionalOptions($data),
            ],
        ];

        return $products;
    }

    /**
     * Get product minimal price.
     *
     * @param  int  $qty
     * @return float
     */
    public function getPriceIncrement(array $options)
    {
        logger()->channel('custom')->info(json_encode(compact($options)));
        $increment = 0;
        if (empty($options)) {
            return $increment;
        }
        $productOptions = $this->productOptionValueRepository->getOptionValues($this->product);
        $optionMap = $productOptions->reduce(function (array $carry, ProductOptionValue $item) {
            $key = $item->option_id;
            $value = $item->value;
            if (is_array($value)) {
                $value = array_reduce($value, function($acc, $val) {
                    $acc[$val['id']] = $val;
                    return $acc;
                }, []);
            }
            $carry[$key] = $value;
            return $carry;
        }, []);
        logger()->channel('custom')->info(json_encode(compact($optionMap)));
        foreach ($options as $key => $value) {
            $val = $optionMap[$key];
            [$prefix, $price] = $val[$value] ?: $val;
            logger()->channel('custom')->info(json_encode([ 'option_increment' => "$prefix$price" ]));
            $incremet += floatval("$prefix$price");
        }
        logger()->channel('custom')->info(json_encode([ 'increment' => $increment ]));
        return $increment;
    }

    /**
     * Returns additional information for items.
     *
     * @param  array  $data
     * @return array
     */
    public function getAdditionalOptions($data)
    {
        if (empty($data['options'])) {
            return $data;
        }
        $productOptions = $this->productOptionValueRepository->getOptionValues($this->product, true);
        logger()->channel('custom')->info(json_encode(compact($productOptions)));
        $optionMap = $productOptions->reduce(function (array $carry, ProductOptionValue $item) {
            $key = $item->option_id;
            $value = [ 'option' => $item->option ];
            if (in_array(['select'], $item->option->type)) {
                $value['values'] = array_reduce($item->option->values, function($acc, $val) {
                    $acc[$val['id']] = $val;
                    return $acc;
                }, []);
            }
            $carry[$key] = $value;
            return $carry;
        }, []);
        logger()->channel('custom')->info(json_encode(compact($optionMap)));
        foreach ($data['options'] as $key => $value) {
            $option = $optionMap[$key]['option'];
            $val = $optionMap[$key]['values'] ? $optionMap[$key]['values'][$value]['label'];
            $data['options'][$option['code']] = [
                'option_name' => $option['name'],
                'option_id'      => $key,
                'option_label'   => $val,
            ];
        }
        return $data;
    }

    /**
     * Get product minimal price.
     *
     * @return string
     */
    public function getPriceHtml()
    {
        return view('shop::products.prices.optionable', [
            'product' => $this->product,
            'prices'  => $this->getProductPrices(),
        ])->render();
    }

    /**
     * Returns price indexer class for a specific product type
     *
     * @return string
     */
    public function getPriceIndexer()
    {
        return app(SimpleIndexer::class);
    }
}
