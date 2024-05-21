<?php

namespace Gaiproject\Option\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Container\Container;

class ProductOptionValueRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @param  \Gaiproject\Option\Repositories\OptionRepository $optionRepository
     * @param  \Webkul\Attribute\Repositories\AttributeFamilyRepository $attributeFamilyRepository
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function __construct(
        protected OptionRepository $optionRepository,
        protected OptionGroupRepository $optionGroupRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Retrieve product attributes.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return \Illuminate\Support\Collection
     */
    public function getFamilyOptions($product)
    {
        $data = $this->optionGroupRepository->getModel()->with(['custom_options'])->whereBelongsTo($product->attribute_family)->get();
        return $data;
    }

    /**
     * Retrieve product attributes.
     *
     * @param  \Webkul\Attribute\Contracts\Group  $group
     * @param  bool  $skipSuperAttribute
     * @return \Illuminate\Support\Collection
     */
    public function getConfigurableOptions()
    {
        //fetch all options that can be configured for a product (for family that have no configured options) and inject into blade template

        return $this->optionRepository->with('values')->all(['id', 'code', 'admin_name', 'type', 'is_sys_defined']);
    }

    /**
     * Retrieve option configurations.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return \Illuminate\Support\Collection
     */
    public function getOptionValues($product, $loadOption = false)
    {
        //fetch all options configuration for a product
        return $this->getModel()->when($loadOption, fn ($query) => $query->with(['option']))->whereBelongsTo($product)->get();
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Gaiproject\Option\Contracts\ProductOptionValue';
    }
}
