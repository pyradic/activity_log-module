<?php

namespace Pyro\ActivityLogModule\Activity\Table;

use Anomaly\Streams\Platform\Ui\Table\Component\Action\Handler\Delete;

class ActivityTableCleanAllHandler extends ActivityTableHandler
{

    public function init()
    {
        $this->exporter->setActivities($this->getRepository()->all());
        $exported = $this->exporter->export();
        $this->getRepository()->truncate();
        dispatch(new QueuedDelete($exported->get('ids')));
        $this->messages->success(trans('streams::message.delete_success', $exported[ 'count' ]));
    }
}
