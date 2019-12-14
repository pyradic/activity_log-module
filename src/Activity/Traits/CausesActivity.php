<?php

namespace Pyro\ActivityLogModule\Activity\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Pyro\ActivityLogModule\ActivityLogModule;

/** @mixin \Illuminate\Database\Eloquent\Model */
trait CausesActivity
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     * @throws \Pyro\ActivityLogModule\Activity\Exceptions\InvalidConfiguration
     */
    public function actions(): MorphMany
    {
        return $this->morphMany(
            ActivityLogModule::determineActivityModel(),
            'causer'
        );
    }
}
