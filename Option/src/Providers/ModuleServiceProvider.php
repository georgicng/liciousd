<?php

namespace Gaiproject\Option\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Gaiproject\Option\Models\Option::class,
        \Gaiproject\Option\Models\OptionValue::class,
    ];
}
