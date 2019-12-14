<?php

namespace Pyro\ActivityLogModule\Activity\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Pyro\ActivityLogModule\Activity\Contract\Activity;

class InvalidConfiguration extends Exception
{
    public static function modelIsNotValid(string $className)
    {
        return new static("The given model class `$className` does not implement `".Activity::class.'` or it does not extend `'.Model::class.'`');
    }
}
