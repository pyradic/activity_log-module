<?php

namespace Pyro\ActivityLogModule\Facades;

use Illuminate\Support\Facades\Facade;

class ActivityLogger extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Pyro\ActivityLogModule\Activity\ActivityLogger::class;
    }

}
