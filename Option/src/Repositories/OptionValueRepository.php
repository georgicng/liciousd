<?php

namespace Gaiproject\Option\Repositories;

use Webkul\Core\Eloquent\Repository;

class OptionValueRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Gaiproject\Option\Contracts\OptionValue';
    }
}