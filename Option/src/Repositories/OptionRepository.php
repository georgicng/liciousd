<?php

namespace Gaiproject\Option\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Container\Container;

class OptionRepository extends Repository
{
     /**
     * Create a new repository instance.
     *
     * @param  \Gaiproject\Option\Repositories\OptionValueRepository  $optionValueRepository
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function __construct(
        protected OptionValueRepository $optionValueRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Gaiproject\Option\Contracts\Option';
    }

    /**
     * Create option.
     *
     * @param  array  $data
     * @return \Gaiproject\Option\Contracts\Option
     */
    public function create(array $data)
    {
        $data = $this->validateUserInput($data);

        $options = $data['options'] ?? [];

        unset($data['options']);

        $option = $this->model->create($data);

        if (in_array($option->type, ['select', 'multiselect', 'checkbox'])) {
            foreach ($options as $optionInputs) {
                $this->optionValueRepository->create(array_merge([
                    'option_id' => $option->id,
                ], $optionInputs));
            }
        }

        return $option;
    }

     /**
     * Update option.
     *
     * @param  array  $data
     * @param  int  $id
     * @param  string  $option
     * @return \Gaiproject\Option\Contracts\Option
     */
    public function update(array $data, $id, $option = 'id')
    {
        $data = $this->validateUserInput($data);

        $option = $this->find($id);

        $option->update($data);

        if (! in_array($option->type, ['select', 'multiselect', 'checkbox'])) {
            return $option;
        }

        if (! isset($data['options'])) {
            return $option;
        }

        foreach ($data['options'] as $optionId => $optionInputs) {
            $isNew = $optionInputs['isNew'] == 'true';

            if ($isNew) {
                $this->optionValueRepository->create(array_merge([
                    'option_id' => $option->id,
                ], $optionInputs));
            } else {
                $isDelete = $optionInputs['isDelete'] == 'true';

                if ($isDelete) {
                    $this->optionValueRepository->delete($optionId);
                } else {
                    $this->optionValueRepository->update($optionInputs, $optionId);
                }
            }
        }

        return $option;
    }



    /**
     * Validate user input.
     *
     * @param  array  $data
     * @return array
     */
    public function validateUserInput($data)
    {
        if (in_array($data['type'], ['select', 'multiselect', 'boolean'])) {
            unset($data['value_per_locale']);
        }

        return $data;
    }
}
