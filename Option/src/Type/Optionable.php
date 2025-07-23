<?php

namespace Gaiproject\Option\Type;

use Webkul\Product\Type\AbstractType;
use Illuminate\Support\Facades\Log;
use Gaiproject\Option\Repositories\ProductOptionValueRepository;
use Gaiproject\Option\PriceIncrementEvaluator;
use Gaiproject\Option\Contracts\ProductOptionValue;
use \Gaiproject\Option\Repositories\OptionRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductVideoRepository;
use Webkul\Product\Repositories\ProductCustomerGroupPriceRepository;
use Gaiproject\Option\Helpers\Indexers\Price\Optionable as OptionableIndexer;
use Webkul\Product\DataTypes\CartItemValidationResult;
use Webkul\Product\Models\ProductFlat;
use Webkul\Product\Facades\ProductImage;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Tax\Helpers\Tax;

class Optionable extends AbstractType
{
    /**
     * Show quantity box.
     *
     * @var bool
     */
    protected $showQuantityBox = true;

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
     * @param  \Gaiproject\Option\Repositories\OptionRepository  $optionRepository
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
        protected OptionRepository  $optionRepository,
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
                fn ($option) => $this->getOptionDefaults($option['id'], $product->id, $option['type']),
                $group['custom_options']->toArray()
            )
        )->toArray();
        $insert = array_merge(
            [$this->getOptionDefaults($this->getConfigOptionId(), $product->id)],
            $optionValues
        );
        $this->productOptionValueRepository->insert($insert);
        return $product;
    }


    //Maybe add this as an attribute
    private function getConfigOptionId()
    {
        $id = $this->optionRepository->Where('code', 'config')->first()->id;
        logger()->channel('custom')->info("id: $id");
        return $id;
    }


    //Maybe use seeder/factory
    private function getOptionDefaults($optionId, $productId, $type = "")
    {
        return  [
            'required' => 0,
            'value' => in_array($type, ['select', 'multiselect', 'checkbox']) ? json_encode([]) : json_encode(new \stdClass()),
            'product_id' => $productId,
            'option_id' => $optionId,
            'position' => 0,
            'required' => 0,
            'min' => "",
            'max' => "",
        ];
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

        if (!$this->haveSufficientQuantity($data['quantity'])) {
            return trans('licious::app.checkout.cart.inventory-warning');
        }

         if (!isset($data['options'])) {
            return trans('licious::app.checkout.cart.options-warning');
         }

         if (!$this->validOptions($data['options'])) {
            return trans('licious::app.checkout.cart.options-error');
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
     * Validate options.
     *
     * @param  array  $options
     * @return boolean
     */
    public function validOptions($options)
    {
        $productOptions = $this->productOptionValueRepository->getOptionValues($this->product, true);

        $optionMap = $productOptions->reduce(function (array $carry, ProductOptionValue $item) {
            $key = $item->option_id;
            $value = [
                'type' => $item->option->type,
                'required' => $item->option->required,
                'min' => $item->min,
                'max' => $item->max,
            ];

            $carry[$key] = $value;
            return $carry;
        }, []);

        foreach ($options as $key => $value) {
            if ($optionMap[$key]['required'] && !isset($value)) {
                return false;
            }
            if ($optionMap[$key]['min'] && isset($value) && count($value) < $optionMap[$key]['min']) {
                return false;
            }
             if ($optionMap[$key]['max'] && isset($value) && count($value) > $optionMap[$key]['max']) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get product minimal price.
     *
     * @param  int  $qty
     * @return float
     */
    public function getFinalPrice($qty = null)
    {
        if (
            is_null($qty)
            || $qty == 1
        ) {
            return $this->getMinimalPrice();
        }

        $customerGroup = $this->customerRepository->getCurrentGroup();

        $indexer = $this->getPriceIndexer()
            ->setCustomerGroup($customerGroup)
            ->setProduct($this->product);
            logger()->channel('custom')->info('suspect');

            logger()->channel('custom')->info(json_encode($indexer->getMinimalPrice($qty)));
            logger()->channel('custom')->info('bus stop');

        return $indexer->getMinimalPrice($qty);
    }

    /**
     * Validate cart item product price and other things.
     *
     * @param  \Webkul\Checkout\Models\CartItem  $item
     * @return \Webkul\Product\DataTypes\CartItemValidationResult
     */
    public function validateCartItem(CartItem $item): CartItemValidationResult
    {
        logger()->channel('custom')->info('start');
        $result = new CartItemValidationResult();

        if ($this->isCartItemInactive($item)) {
            $result->itemIsInactive();

            return $result;
        }

        logger()->channel('custom')->info('mid');

        $price = round($this->getFinalPrice($item->quantity), 4) + round($this->getPriceIncrement($item->additional['options']), 4);
        logger()->channel('custom')->info("price: $price");

        if ($price == $item->base_price) {
            logger()->channel('custom')->info('cut short');
            return $result;
        }

        logger()->channel('custom')->info('still running');

        $item->base_price = $price;
        $item->price = core()->convertPrice($price);

        $item->base_total = $price * $item->quantity;
        $item->total = core()->convertPrice($price * $item->quantity);

        $item->save();
        logger()->channel('custom')->info('end');

        return $result;
    }

    /**
     * Get product minimal price.
     *
     * @param  int  $qty
     * @return float
     */
    public function getPriceIncrement(array $options)
    {
        $increment = 0;
        if (empty($options)) {
            return $increment;
        }
        $productOptions = $this->productOptionValueRepository->getOptionValues($this->product);
        $optionMap = $productOptions->reduce(function (array $carry, ProductOptionValue $item) {
            $key = $item->option_id;
            $value = $item->value;
             logger()->channel('custom')->info(json_encode(compact('key', 'value')));
            if (!empty($value) && is_array($value) && array_is_list($value)) {
                $value = array_reduce($value, function ($acc, $val) {
                    $acc[$val['id']] = $val;
                    return $acc;
                }, []);
            }
            $carry[$key] = $value;
            return $carry;
        }, []);
        logger()->channel('custom')->info(json_encode(compact('optionMap')));
        $config = $optionMap[$this->getConfigOptionId()];
        if (!empty($config['dynamic']) && $config['dynamic'] === "on") {
            return PriceIncrementEvaluator::getResult($config['rules'], $options);
        }
        foreach ($options as $key => $value) {
            if (empty($value))  continue;
            $values = is_array($value) ? $value : [$value];
            $option = $optionMap[$key];
            $sub = array_reduce($values, function ($acc, $val) use ($option) {
                $optionValue = isset($option[$val]) ? $option[$val] : $option;
                return $acc + $optionValue['base_increment'];
            }, 0);
            $increment += $sub;
        }
        logger()->channel('custom')->info("increment: $increment");
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
        if (empty($data['options'])) return $data;
        $productOptions = $this->productOptionValueRepository->getOptionValues($this->product, true);
        $optionMap = $productOptions->reduce(function (array $carry, ProductOptionValue $item) {
            $key = $item->option_id;
            $value = ['option' => $item->option];
            if (in_array($item->option->type, ['select', 'multiselect', 'checkbox'])) {
                $value['values'] = $item->option->values->reduce(function ($acc, $val) {
                    $acc[$val['id']] = $val;
                    return $acc;
                }, []);
            }
            $carry[$key] = $value;
            return $carry;
        }, []);
        foreach ($data['options'] as $key => $value) {
            if (empty($value))  continue;
            $values = is_array($value) ? $value : [$value]; //&& array_is_list($value)
            $option = $optionMap[$key]['option'];
            $optionValue = array_map(
                fn($val) => isset($optionMap[$key]['values']) ? $optionMap[$key]['values'][$val]['label'] : $val,
                $values
            );
            $data['attributes'][$option['code']] = [
                'attribute_name' => $option['name'],
                'option_id'      => $key,
                'option_label'   => implode(', ', $optionValue),
            ];
        }
        return $data;
    }

    /**
     * Compare options.
     *
     * @param  array  $options1
     * @param  array  $options2
     * @return bool
     */
    public function compareOptions($options1, $options2)
    {
        if ($this->product->id != $options2['product_id']) {
            return false;
        }

        if (
            isset($options1['options'])
            && isset($options2['options'])
        ) {
            foreach( array_keys($options1['options']) as $key ) {
                if (is_array($options1['options'][$key]) && is_array($options2['options'][$key])) {
                    if (count(array_diff($options1['options'][$key], $options2['options'][$key]))) {
                        return false;
                    }
                }
                if (
                    $options1['options'][$key] != $options2['options'][$key]
                ) {
                    return false;
                }
            }

        }

        /*
        if (
            isset($options1['parent_id'])
            && isset($options2['parent_id'])
        ) {
            return $options1['parent_id'] == $options2['parent_id'];
        }

        if (
            isset($options1['parent_id'])
            && !isset($options2['parent_id'])
        ) {
            return false;
        }

        if (
            isset($options2['parent_id'])
            && !isset($options1['parent_id'])
        ) {
            return false;
        }
        */

        return true;
    }



    /**
     * Returns price indexer class for a specific product type
     *
     * @return string
     */
    public function getPriceIndexer()
    {
        return app(OptionableIndexer::class);
    }
}
