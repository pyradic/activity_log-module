<?php

namespace Pyro\ActivityLogModule\Activity\Table;

class ActivityTableBackupAllHandler extends ActivityTableHandler
{

    public function init()
    {
        $this->exporter->setActivities($this->getRepository()->all());
        $this->exportToDownload();
    }
}
