<?php

namespace Gaiproject\Option\Type;

use Webkul\Product\Type\AbstractType;
use Gaiproject\Option\Repositories\ProductOptionValueRepository;
use \Gaiproject\Option\Repositories\OptionRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductVideoRepository;
use Webkul\Product\Repositories\ProductCustomerGroupPriceRepository;
use Gaiproject\Option\Helpers\Indexers\Price\Optionable as SimpleIndexer;
class Quotable extends AbstractType
{
    /**
     * Show quantity box.
     *
     * @var bool
     */
    protected $showQuantityBox = false;

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
        logger()->channel('custom')->info(json_encode([ 'insert' => $insert ]));
        $this->productOptionValueRepository->insert($insert);
        return $product;
    }


    //Maybe add this as an attribute
    private function getConfigOptionId()
    {
        return $this->optionRepository->Where('code', 'config')->first()->id;
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
     * Get product minimal price.
     *
     * @return string
     */
    public function getPriceHtml()
    {
        return view('shop::products.prices.optionable', [
            'product' => $this->product,
            'prices'  => $this->getProductPrices(),
            'currency' => core()->getCurrentCurrency(),
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
