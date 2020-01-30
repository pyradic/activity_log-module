<?php namespace Pyro\ActivityLogModule\Activity;

use Anomaly\Streams\Platform\Model\ActivityLog\ActivityLogActivityEntryModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Pyro\ActivityLogModule\Activity\Contract\Activity;
use Pyro\ActivityLogModule\Activity\Contract\ActivityInterface;

/**
 * Pyro\ActivityLogModule\Activity\ActivityModel
 *
 * @property int $id
 * @property int|null $sort_order
 * @property \Illuminate\Support\Carbon $created_at
 * @property int|null $created_by_id
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $updated_by_id
 * @property string $log_name
 * @property string $description
 * @property int|null $subject_id
 * @property string|null $subject_type
 * @property int|null $causer_id
 * @property string|null $causer_type
 * @property \Illuminate\Support\Collection|null $properties
 * @property \Pyro\ActivityLogModule\Activity\ActivityCollection|\Pyro\ActivityLogModule\Activity\ActivityModel[] $actions
 * @property int|null $actions_count
 * @property \Pyro\ActivityLogModule\Activity\ActivityCollection|\Pyro\ActivityLogModule\Activity\ActivityModel[] $activityLogs
 * @property int|null $activity_logs_count
 * @property \Illuminate\Database\Eloquent\Model|\Eloquent $causer
 * @property \Anomaly\UsersModule\User\UserModel|null $createdBy
 * @property \Anomaly\UsersModule\User\UserModel|null $created_by
 * @property mixed $changes
 * @property mixed|null $raw
 * @property \Illuminate\Database\Eloquent\Model|\Eloquent $subject
 * @property \Anomaly\UsersModule\User\UserModel|null $updatedBy
 * @property \Anomaly\UsersModule\User\UserModel|null $updated_by
 * @property \Anomaly\Streams\Platform\Version\VersionCollection|\Anomaly\Streams\Platform\Version\VersionModel[] $versions
 * @property int|null $versions_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Pyro\ActivityLogModule\Activity\ActivityModel causedBy(\Illuminate\Database\Eloquent\Model $causer)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pyro\ActivityLogModule\Activity\ActivityModel forSubject(\Illuminate\Database\Eloquent\Model $subject)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pyro\ActivityLogModule\Activity\ActivityModel inLog($logNames)
 * @method static \Pyro\ActivityLogModule\Activity\ActivityModel make($attributes=[])
 * @method static \Anomaly\Streams\Platform\Entry\EntryQueryBuilder|\Pyro\ActivityLogModule\Activity\ActivityModel newModelQuery()
 * @method static \Anomaly\Streams\Platform\Entry\EntryQueryBuilder|\Pyro\ActivityLogModule\Activity\ActivityModel newQuery()
 * @method static \Anomaly\Streams\Platform\Entry\EntryQueryBuilder|\Pyro\ActivityLogModule\Activity\ActivityModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Anomaly\Streams\Platform\Entry\EntryModel sorted($direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\Anomaly\Streams\Platform\Model\EloquentModel translated()
 * @method static \Illuminate\Database\Eloquent\Builder|\Anomaly\Streams\Platform\Model\EloquentModel translatedIn($locale)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pyro\ActivityLogModule\Activity\ActivityModel whereCauserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pyro\ActivityLogModule\Activity\ActivityModel whereCauserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pyro\ActivityLogModule\Activity\ActivityModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pyro\ActivityLogModule\Activity\ActivityModel whereCreatedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pyro\ActivityLogModule\Activity\ActivityModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pyro\ActivityLogModule\Activity\ActivityModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pyro\ActivityLogModule\Activity\ActivityModel whereLogName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pyro\ActivityLogModule\Activity\ActivityModel whereProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pyro\ActivityLogModule\Activity\ActivityModel whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pyro\ActivityLogModule\Activity\ActivityModel whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pyro\ActivityLogModule\Activity\ActivityModel whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pyro\ActivityLogModule\Activity\ActivityModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pyro\ActivityLogModule\Activity\ActivityModel whereUpdatedById($value)
 * @mixin \Eloquent
 */
class ActivityModel extends ActivityLogActivityEntryModel implements ActivityInterface, Activity
{
    protected $casts = [
        'properties' => 'collection',
    ];

    protected $enableLoggingModelsEvents = false;

    public function subject()
    {
        if (config('pyro.module.activity_log::config.subject_returns_soft_deleted_models')) {
            return $this->morphTo()->withTrashed();
        }

        return $this->morphTo();
    }

    public function causer()
    {
        return $this->morphTo();
    }

    public function getExtraProperty(string $propertyName)
    {
        return Arr::get($this->properties->toArray(), $propertyName);
    }

    public function changes(): Collection
    {
        if (! $this->properties instanceof Collection) {
            return new Collection();
        }

        return $this->properties->only(['attributes', 'old']);
    }

    public function getChangesAttribute(): Collection
    {
        return $this->changes();
    }

    public function scopeInLog(Builder $query, ...$logNames): Builder
    {
        if (is_array($logNames[0])) {
            $logNames = $logNames[0];
        }

        return $query->whereIn('log_name', $logNames);
    }

    public function scopeCausedBy(Builder $query, Model $causer): Builder
    {
        return $query
            ->where('causer_type', $causer->getMorphClass())
            ->where('causer_id', $causer->getKey());
    }

    public function scopeForSubject(Builder $query, Model $subject): Builder
    {
        return $query
            ->where('subject_type', $subject->getMorphClass())
            ->where('subject_id', $subject->getKey());
    }

}
