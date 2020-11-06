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
 * @property int                                                                                                  $id
 * @property int|null                                                                                             $sort_order
 * @property mixed                                                                                                $created_at
 * @property int|null                                                                                             $created_by_id
 * @property mixed                                                                                                $updated_at
 * @property int|null                                                                                             $updated_by_id
 * @property string                                                                                               $log_name
 * @property string                                                                                               $description
 * @property int|null                                                                                             $subject_id
 * @property string|null                                                                                          $subject_type
 * @property int|null                                                                                             $causer_id
 * @property string|null                                                                                          $causer_type
 * @property Collection|null                                                                                      $properties
 * @property \Pyro\ActivityLogModule\Activity\ActivityCollection|ActivityModel[]                                  $actions
 * @property \Pyro\ActivityLogModule\Activity\ActivityCollection|ActivityModel[]                                  $activityLogs
 * @property Model|\Eloquent                                                                                      $causer
 * @property \Anomaly\UsersModule\User\UserModel|null                                                             $createdBy
 * @property \Anomaly\UsersModule\User\UserModel|null                                                             $created_by
 * @property Collection                                                                                           $changes
 * @property mixed|null                                                                                           $raw
 * @property Model|\Eloquent                                                                                      $subject
 * @property \Anomaly\UsersModule\User\UserModel|null                                                             $updatedBy
 * @property \Anomaly\UsersModule\User\UserModel|null                                                             $updated_by
 * @property \Anomaly\Streams\Platform\Version\VersionCollection|\Anomaly\Streams\Platform\Version\VersionModel[] $versions
 * @method static \Pyro\ActivityLogModule\Activity\ActivityCollection|static[] all($columns = [ '*' ])
 * @method static Builder|ActivityModel causedBy(\Illuminate\Database\Eloquent\Model $causer)
 * @method static Builder|ActivityModel forSubject(\Illuminate\Database\Eloquent\Model $subject)
 * @method static \Pyro\ActivityLogModule\Activity\ActivityCollection|static[] get($columns = [ '*' ])
 * @method static Builder|ActivityModel inLog($logNames)
 * @method static \Pyro\ActivityLogModule\Activity\ActivityModel make($attributes = [])
 * @method static \Anomaly\Streams\Platform\Entry\EntryQueryBuilder|ActivityModel newModelQuery()
 * @method static \Anomaly\Streams\Platform\Entry\EntryQueryBuilder|ActivityModel newQuery()
 * @method static \Anomaly\Streams\Platform\Entry\EntryQueryBuilder|ActivityModel query()
 * @method static Builder|EntryModel sorted($direction = 'asc')
 * @method static Builder|EloquentModel translated()
 * @method static Builder|EloquentModel translatedIn($locale)
 * @method static Builder|ActivityModel whereCauserId($value)
 * @method static Builder|ActivityModel whereCauserType($value)
 * @method static Builder|ActivityModel whereCreatedAt($value)
 * @method static Builder|ActivityModel whereCreatedById($value)
 * @method static Builder|ActivityModel whereDescription($value)
 * @method static Builder|ActivityModel whereId($value)
 * @method static Builder|ActivityModel whereLogName($value)
 * @method static Builder|ActivityModel whereProperties($value)
 * @method static Builder|ActivityModel whereSortOrder($value)
 * @method static Builder|ActivityModel whereSubjectId($value)
 * @method static Builder|ActivityModel whereSubjectType($value)
 * @method static Builder|ActivityModel whereUpdatedAt($value)
 * @method static Builder|ActivityModel whereUpdatedById($value)
 * @mixin \Eloquent
 * @method \Pyro\ActivityLogModule\Activity\ActivityPresenter getPresenter()
 * @method \Pyro\ActivityLogModule\Activity\ActivityCollection newCollection()
 * @method \Anomaly\Streams\Platform\Entry\EntryRouter newRouter()
 * @method \Anomaly\Streams\Platform\Entry\EntryQueryBuilder newEloquentBuilder()
 */
class ActivityModel extends ActivityLogActivityEntryModel implements ActivityInterface, Activity
{
    protected $casts = [
        'properties' => 'collection',
    ];

    protected $enableLoggingModelsEvents = false;

    public function getPropertiesAttribute()
    {
        return collect(json_decode($this->attributes[ 'properties' ], true));
    }

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
        if ( ! $this->properties instanceof Collection) {
            return new Collection();
        }

        return $this->properties->only([ 'attributes', 'old' ]);
    }

    public function getChangesAttribute(): Collection
    {
        return $this->changes();
    }

    public function scopeInLog(Builder $query, ...$logNames): Builder
    {
        if (is_array($logNames[ 0 ])) {
            $logNames = $logNames[ 0 ];
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
