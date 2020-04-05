<?php /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

namespace Pyro\ActivityLogModule;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use Laradic\Config\Repository;
use Pyro\ActivityLogModule\Activity\ActivityCollection;
use Pyro\ActivityLogModule\Activity\ActivityLogger;
use Pyro\ActivityLogModule\Activity\ActivityLogStatus;
use Pyro\ActivityLogModule\Activity\ActivityModel;
use Pyro\ActivityLogModule\Activity\ActivityRepository;
use Pyro\ActivityLogModule\Activity\Contract\ActivityInterface;
use Pyro\ActivityLogModule\Activity\Contract\ActivityRepositoryInterface;
use Pyro\ActivityLogModule\Activity\Export\ActivityExporter;
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
        if($this->app->config instanceof \Laradic\Config\Repository){
            /** @var \Laradic\Config\Parser $parser */
            $parser = $this->app->config->getParser();
            $parser->exclude('pyro.module.activity_log::config.export_filename_template');
        }
        $this->app->extend(ActivityLogger::class, function (ActivityLogger $logger) {
            $logger->inLog(config('pyro.module.activity_log::config.default_log_name'));
            return $logger;
        });
        $this->app->bind(ActivityExporter::class, function (Application $app) {
            $exporter = new ActivityExporter($app[ ActivityRepositoryInterface::class ], $app[ ActivityCollection::class ], $app[ 'files' ]);
            $exporter->setDirectory(config('pyro.module.activity_log::config.export_directory'));
            $exporter->setFileNameTemplate(config('pyro.module.activity_log::config.export_filename_template'));
            $exporter->setFormat(config('pyro.module.activity_log::config.export_format'));
            return $exporter;
        });
    }

    public function boot()
    {
    }

    public function map(Router $router)
    {
    }

}
