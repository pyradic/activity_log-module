<?php

namespace Pyro\ActivityLogModule\Providers;

class ActivityLogServiceProvider extends \Spatie\Activitylog\ActivitylogServiceProvider
{
    public function boot()
    {
//        $this->publishes([
//            __DIR__.'/../config/activitylog.php' => config_path('activitylog.php'),
//        ], 'config');
//
//        $this->mergeConfigFrom(__DIR__.'/../config/activitylog.php', 'activitylog');
//
//        if (! class_exists('CreateActivityLogTable')) {
//            $timestamp = date('Y_m_d_His', time());
//
//            $this->publishes([
//                __DIR__.'/../migrations/create_activity_log_table.php.stub' => database_path("/migrations/{$timestamp}_create_activity_log_table.php"),
//            ], 'migrations');
//        }
    }
}
