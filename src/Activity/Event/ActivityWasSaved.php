<?php

namespace Pyro\ActivityLogModule\Activity\Event;

use \Pyro\ActivityLogModule\Activity\Contract\ActivityInterface;

/**
 *
 */
class ActivityWasSaved
{

    /**
     * @var \Pyro\ActivityLogModule\Activity\Contract\ActivityInterface
     */
    protected $activity;


    public function getActivity()
    {
        return $this->activity;
    }

    public function __construct(ActivityInterface $activity)
    {
        $this->activity = $activity;
    }

}
