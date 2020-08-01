<?php /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

namespace Pyro\ActivityLogModule;

use Anomaly\Streams\Platform\Addon\AddonCollection;
use Anomaly\Streams\Platform\Addon\AddonServiceProvider;
use Crvs\Platform\Entry\Command\AddTraitsToEntryModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Pyro\ActivityLogModule\Activity\ActivityCollection;
use Pyro\ActivityLogModule\Activity\ActivityLogger;
use Pyro\ActivityLogModule\Activity\ActivityLogStatus;
use Pyro\ActivityLogModule\Activity\ActivityModel;
use Pyro\ActivityLogModule\Activity\ActivityRepository;
use Pyro\ActivityLogModule\Activity\Contract\ActivityInterface;
use Pyro\ActivityLogModule\Activity\Contract\ActivityRepositoryInterface;
use Pyro\ActivityLogModule\Activity\Export\ActivityExporter;
use Pyro\ActivityLogModule\Activity\Traits\CausesActivity;
use Pyro\ActivityLogModule\Activity\Traits\LogsActivity;
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
//        '/admin/activity_log/create'    => [ 'as' => 'pyro.module.activity_log::activity.create', 'uses' => ActivityController::class . '@create' ],
        '/admin/activity_log/edit/{id}' => [ 'as' => 'pyro.module.activity_log::activity.edit', 'uses' => ActivityController::class . '@edit' ],
    ];

    public function register(AddonCollection $addons)
    {
        AliasLoader::getInstance()->alias('ActivityLogger', \Pyro\ActivityLogModule\Facades\ActivityLogger::class);
        foreach ($addons->withConfig('activity_log') as $addon) {
            /** @var \Anomaly\Streams\Platform\Addon\Addon $addon */
            foreach (config($addon->getNamespace('activity_log'), []) as $eventName => $routeCb) {
                $this->app->events->listen($eventName, function ($event) use ($routeCb) {
                    $defaults = [ 'by' => \Auth::id() ];
                    $config   = array_replace_recursive($defaults, $routeCb($event));
                    $logger   = resolve(ActivityLogger::class);
                    $log      = Arr::pull($config, 'log', 'logged');
                    foreach ($config as $key => $value) {
                        call_user_func([ $logger, $key ], $value);
                    }
                    $logger->log($log);
                });
            }
        }
        if ($this->app->config instanceof \Laradic\Config\Repository) {
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
        dispatch_now(new AddTraitsToEntryModel([
            CausesActivity::class,
            LogsActivity::class,
        ]));
    }

    public function boot()
    {
    }

    public function map(Router $router)
    {
    }

}
