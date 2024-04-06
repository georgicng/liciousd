<?php

namespace Gaiproject\CityShipping\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Gaiproject\CityShipping\Models\ShippingCity::class,
    ];
}
