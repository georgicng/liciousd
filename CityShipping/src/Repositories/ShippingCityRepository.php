<?php

namespace Gaiproject\CityShipping\Repositories;

use Webkul\Core\Eloquent\Repository;

class ShippingCityRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Gaiproject\CityShipping\Contracts\ShippingCity';
    }
}
