<?php

namespace Pyro\ActivityLogModule\Activity;

use Illuminate\Contracts\Config\Repository;

class ActivityLogStatus
{
    protected $enabled = true;

    public function __construct(Repository $config)
    {
        $this->enabled = $config->get('pyro.module.activity_log::config.enabled');
    }

    public function enable(): bool
    {
        return $this->enabled = true;
    }

    public function disable(): bool
    {
        return $this->enabled = false;
    }

    public function disabled(): bool
    {
        return $this->enabled === false;
    }
}
