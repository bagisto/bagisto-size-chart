<?php

namespace Webkul\SizeChart\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\SizeChart\Models\SizeChart::class,
        \Webkul\SizeChart\Models\AssignTemplate::class
    ];
}