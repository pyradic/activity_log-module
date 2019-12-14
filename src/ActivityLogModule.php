<?php namespace Pyro\ActivityLogModule;

use Anomaly\Streams\Platform\Addon\Module\Module;
use Illuminate\Database\Eloquent\Model;
use Pyro\ActivityLogModule\Activity\ActivityModel;
use Pyro\ActivityLogModule\Activity\Contract\Activity;
use Pyro\ActivityLogModule\Activity\Contract\ActivityInterface;
use Pyro\ActivityLogModule\Activity\Exceptions\InvalidConfiguration;

class ActivityLogModule extends Module
{

    /**
     * The navigation display flag.
     *
     * @var bool
     */
    protected $navigation = true;

    /**
     * The addon icon.
     *
     * @var string
     */
    protected $icon = 'fa fa-puzzle-piece';

    /**
     * The module sections.
     *
     * @var array
     */
    protected $sections = [
        'activity' => [
            'buttons' => [
                'new_activity',
            ],
        ],
    ];

    /**
     * @return string
     * @throws \Pyro\ActivityLogModule\Activity\Exceptions\InvalidConfiguration
     */
    public static function determineActivityModel(): string
    {
        $activityModel = config('pyro.module.activity_log::config.activity_model') ?? ActivityModel::class;

        if (! is_a($activityModel, ActivityModel::class, true)
            || ! is_a($activityModel, Model::class, true)) {
            throw InvalidConfiguration::modelIsNotValid($activityModel);
        }

        return $activityModel;
    }

    public static function getActivityModelInstance(): Activity
    {
        $activityModelClassName = self::determineActivityModel();

        return new $activityModelClassName();
    }
}
