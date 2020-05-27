<?php

namespace Pyro\ActivityLogModule\Activity;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Anomaly\Streams\Platform\Entry\EntryObserver;
use Anomaly\UsersModule\User\UserModel;
use Pyro\ActivityLogModule\Activity\Event\ActivityWasCreated;
use Pyro\ActivityLogModule\Activity\Event\ActivityWasDeleted;
use Pyro\ActivityLogModule\Activity\Event\ActivityWasSaved;
use Pyro\ActivityLogModule\Activity\Event\ActivityWasSaving;
use Pyro\ActivityLogModule\Activity\Event\ActivityWasUpdated;

/**
 * 
 *
 */
class ActivityObserver extends EntryObserver
{

    public function deleted(EntryInterface $entry)
    {
        parent::deleted($entry);
        event(new ActivityWasDeleted($entry));
    }

    public function created(EntryInterface $entry)
    {
        parent::created($entry);
        event(new ActivityWasCreated($entry));
    }

    public function updated(EntryInterface $entry)
    {
        parent::updated($entry);
        event(new ActivityWasUpdated($entry));
    }

    public function saving(EntryInterface $entry)
    {
        parent::saving($entry);
        if ($entry->causer_type === null) {
            $entry->causer_id   = 1;
            $entry->causer_type = UserModel::class;
        }
        if ($entry->created_by_id === null) {
            $entry->created_by_id = 1;
        }
        event(new ActivityWasSaving($entry));
    }

    public function saved(EntryInterface $entry)
    {
        parent::saved($entry);
        event(new ActivityWasSaved($entry));
    }

}
