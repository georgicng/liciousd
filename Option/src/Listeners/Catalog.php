<?php

namespace Gaiproject\Option\Listeners;

use Gaiproject\Option\Repositories\OptionGroupRepository;

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
}
