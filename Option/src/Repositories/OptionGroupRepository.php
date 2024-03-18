<?php

namespace Gaiproject\Option\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;

class OptionGroupRepository extends Repository
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
        protected AttributeFamilyRepository $attributeFamilyRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * @param  array  $data
     * @param  \Webkul\Attribute\Contracts\AttributeFamily $attributeFamily
     * @return void
     */
    public function createMany($data, $attributeFamily)
    {
        $optionGroups = $data ?? [];

        foreach ($optionGroups as $group) {
            $customOptions = $group['custom_options'] ?? [];

            unset($group['custom_options']);
            // $group['attribute_family_id'] = $attributeFamily->id;
            //$optionGroup = parent::create($group);
            //$optionGroup = $this->model->whereBelongsTo($attributeFamily)->create($group);
            $optionGroup = $this->model->create(array_merge($group, ['attribute_family_id' => $attributeFamily->id]));

            foreach ($customOptions as $key => $option) {
                if (isset($option['id'])) {
                    $optionModel = $this->optionRepository->find($option['id']);
                } else {
                    $optionModel = $this->optionRepository->findOneByField('code', $option['code']);
                }

                $optionGroup->custom_options()->save($optionModel, ['position' => $key + 1]);
            }
        }
    }

    /**
     * @param  array  $data
     * @param  \Webkul\Attribute\Contracts\AttributeFamily $attributeFamily
     * @return void
     */
    public function updateMany($data, $attributeFamily, $attribute = "id")
    {

        $previousOptionGroupIds = $this->model->whereBelongsTo($attributeFamily)->get()->pluck('id'); //create scope to get groups per family in optiongroup model and swap with this or use the getbyfamily defined below

        foreach ($data ?? [] as $optionGroupId => $optionGroupInputs) {
            if (Str::contains($optionGroupId, 'group_')) { //if new, create
                $optionGroup = $this->model->whereBelongsTo($attributeFamily)->create($optionGroupInputs);

                if (empty($optionGroupInputs['custom_options'])) {
                    continue;
                }

                foreach ($optionGroupInputs['custom_options'] as $optionInputs) {
                    $option = $this->optionRepository->find($optionInputs['id']);

                    $optionGroup->custom_options()->save($option, [
                        'position' => $optionInputs['position'],
                    ]);
                }
            } else { //if existing search and update
                if (is_numeric($index = $previousOptionGroupIds->search($optionGroupId))) {
                    $previousOptionGroupIds->forget($index);
                }

                $optionGroup = $this->update($optionGroupInputs, $optionGroupId);

                $previousOptionIds = $optionGroup->custom_options()->get()->pluck('id');

                foreach ($optionGroupInputs['custom_options'] ?? [] as $optionInputs) {
                    if (is_numeric($index = $previousOptionIds->search($optionInputs['id']))) {
                        $previousOptionIds->forget($index);

                        $optionGroup->custom_options()->updateExistingPivot($optionInputs['id'], [
                            'position' => $optionInputs['position'],
                        ]);
                    } else {
                        $attribute = $this->optionRepository->find($optionInputs['id']);

                        $optionGroup->custom_options()->save($attribute, [
                            'position' => $optionInputs['position'],
                        ]);
                    }
                }

                if ($previousOptionIds->count()) {
                    $optionGroup->custom_options()->detach($previousOptionIds);
                }
            }
        }

        foreach ($previousOptionGroupIds as $optionGroupId) { //cleanup
            $this->delete($optionGroupId);
        }
    }

    /**
     * @param  \Webkul\Attribute\Contracts\AttributeFamily
     * @return array
     */
    public function getByFamily($family = null, $loadOptions = true)
    {
        if (!$family) {
            $family = $this->attributeFamilyRepository->findOneByField('code', 'default');
        }
        //$this->model->when($loadOptions, fn ($query) => $query-> with(['custom_options']))->whereBelongsTo($family)->get();
        return $this->model->with(['custom_options'])->whereBelongsTo($family)->get();
    }


    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Gaiproject\Option\Contracts\OptionGroup';
    }
}
