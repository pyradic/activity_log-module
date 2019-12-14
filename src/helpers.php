<?php

use Pyro\ActivityLogModule\Activity\ActivityLogger;
use Pyro\ActivityLogModule\Activity\ActivityLogStatus;

if (! function_exists('activity')) {
    function activity(string $logName = null): ActivityLogger
    {
        $defaultLogName = config('pyro.module.activity_log::config.default_log_name');

        $logStatus = app(ActivityLogStatus::class);

        return app(ActivityLogger::class)
            ->useLog($logName ?? $defaultLogName)
            ->setLogStatus($logStatus);
    }
}
