<?php

namespace Gaiproject\Option\Listeners;

use Gaiproject\Option\Repositories\OptionGroupRepository;
use Gaiproject\Option\Repositories\ProductOptionValueRepository;

class Catalog
{
    /**
     * Create a new repository instance.
     *
     * @param  \Gaiproject\Option\Repositories\OptionGroupRepository $optionGroupRepository
     * @return void
     */
    public function __construct(
        protected OptionGroupRepository $optionGroupRepository,
        protected ProductOptionValueRepository $ProductOptionValueRepository,
    ) {
    }

    /**
     * Create option groups.
     *
     * @param  \Webkul\Attribute\Contracts\AttributeFamily $attributeFamily
     * @return void
     */
    public function createFamily($attributeFamily)
    {
        $this->optionGroupRepository->createMany(request('option_groups'), $attributeFamily);
    }

    /**
     * Update option groups.
     *
     * @param  \Webkul\Attribute\Contracts\AttributeFamily $attributeFamily
     * @return void
     */
    public function editFamily($attributeFamily)
    {
        $this->optionGroupRepository->updateMany(request('option_groups'), $attributeFamily);
    }

    /**
     * Save product option values.
     *
     * @param \Webkul\Product\Contracts\Product
     * @return void
     */
    public function createProduct($product)
    {
        return;
    }

    /**
     * Save product option values on edit.
     *
     * @param \Webkul\Product\Contracts\Product
     * @return void
     */
    public function editProduct($product)
    {
        return;
    }

    /**
     * delete product option values.
     *
     * @param int $id
     * @return void
     */
    public function deleteProduct($id)
    {
        return;
    }
}
