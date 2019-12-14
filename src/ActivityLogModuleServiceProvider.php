<?php namespace Pyro\ActivityLogModule;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;
use Illuminate\Routing\Router;
use Pyro\ActivityLogModule\Activity\ActivityLogger;
use Pyro\ActivityLogModule\Activity\ActivityLogStatus;
use Pyro\ActivityLogModule\Activity\ActivityModel;
use Pyro\ActivityLogModule\Activity\ActivityRepository;
use Pyro\ActivityLogModule\Activity\Contract\ActivityInterface;
use Pyro\ActivityLogModule\Activity\Contract\ActivityRepositoryInterface;
use Pyro\ActivityLogModule\Http\Controller\Admin\ActivityController;

class ActivityLogModuleServiceProvider extends AddonServiceProvider
{
    protected $providers = [
    ];

    protected $bindings = [
        ActivityLogger::class    => ActivityLogger::class,
        ActivityInterface::class => ActivityModel::class,
    ];

    protected $singletons = [
        ActivityLogStatus::class           => ActivityLogStatus::class,
        ActivityRepositoryInterface::class => ActivityRepository::class,
    ];

    protected $routes = [
        '/admin/activity_log'           => [ 'as' => 'pyro.module.activity_log::activity.index', 'uses' => ActivityController::class . '@index' ],
        '/admin/activity_log/create'    => [ 'as' => 'pyro.module.activity_log::activity.create', 'uses' => ActivityController::class . '@create' ],
        '/admin/activity_log/edit/{id}' => [ 'as' => 'pyro.module.activity_log::activity.edit', 'uses' => ActivityController::class . '@edit' ],
    ];

    public function register()
    {
        $this->app->extend(ActivityLogger::class, function (ActivityLogger $logger) {
            $logger->inLog(config('pyro.module.activity_log::config.default_log_name'));
            return $logger;
        });
    }

    public function boot()
    {
    }

    public function map(Router $router)
    {
    }

}
