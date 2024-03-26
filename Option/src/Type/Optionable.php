<?php

namespace Gaiproject\Option\Type;

use Webkul\Product\Type\AbstractType;

class Optionable extends AbstractType
{
     /**
     * Update configurable product.
     *
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Product\Contracts\Product
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        $product = parent::update($data, $id, $attribute);

        return $product;
    }

}
