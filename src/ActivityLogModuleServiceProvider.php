<?php namespace Pyro\ActivityLogModule;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;
use Illuminate\Routing\Router;
use Pyro\ActivityLogModule\Providers\ActivityLogServiceProvider;

class ActivityLogModuleServiceProvider extends AddonServiceProvider
{

    protected $providers = [
       ActivityLogServiceProvider::class
    ];

    public function register()
    {
    }

    public function boot()
    {
    }

    public function map(Router $router)
    {
    }

}
