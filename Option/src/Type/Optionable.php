<?php

namespace Gaiproject\Option\Type;

use Webkul\Product\Type\AbstractType;
use Illuminate\Support\Facades\Log;
use Gaiproject\Option\Repositories\ProductOptionValueRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductVideoRepository;
use Webkul\Product\Repositories\ProductCustomerGroupPriceRepository;
use Webkul\Product\Helpers\Indexers\Price\Simple as SimpleIndexer;

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
            fn (array $group) => array_map(
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
                $group['custom_options']
            )
        );

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
     * Returns price indexer class for a specific product type
     *
     * @return string
     */
    public function getPriceIndexer()
    {
        return app(SimpleIndexer::class);
    }

}
