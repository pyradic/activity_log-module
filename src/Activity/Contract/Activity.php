<?php

namespace Pyro\ActivityLogModule\Activity\Contract;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface Activity
{
    public function subject();

    public function causer();

    public function getExtraProperty(string $propertyName);

    public function changes(): Collection;

    public function scopeInLog(Builder $query, ...$logNames): Builder;

    public function scopeCausedBy(Builder $query, Model $causer): Builder;

    public function scopeForSubject(Builder $query, Model $subject): Builder;
}
