<?php

namespace Gaiproject\Pickup\Repositories;

use Webkul\Core\Eloquent\Repository;

class PickupCentreRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Gaiproject\Pickup\Contracts\PickupCentre';
    }
}