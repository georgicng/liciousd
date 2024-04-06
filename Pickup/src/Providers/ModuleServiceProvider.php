<?php

namespace Gaiproject\Pickup\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Gaiproject\Pickup\Models\PickupCentre::class,
    ];
}
