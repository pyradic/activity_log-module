<?php

namespace Pyro\ActivityLogModule\Activity\Table;

use Anomaly\Streams\Platform\Ui\Table\Component\Action\ActionHandler;
use Illuminate\Routing\ResponseFactory;
use Pyro\ActivityLogModule\Activity\Export\ActivityExporter;

class ActivityTableBackupHandler  extends ActivityTableHandler
{

    public function init()
    {
        $this->exporter->setActivities($this->getRepository()->findAll($this->selected));
        $this->exportToDownload();
    }
}
