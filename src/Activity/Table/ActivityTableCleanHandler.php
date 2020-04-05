<?php

namespace Pyro\ActivityLogModule\Activity\Table;

use Anomaly\Streams\Platform\Ui\Table\Component\Action\Handler\Delete;

class ActivityTableCleanHandler extends ActivityTableHandler
{
    public function init()
    {
        $this->exporter->setActivities($this->getRepository()->findAll($this->selected));
        $exported = $this->exporter->export();
        resolve(Delete::class)->handle($this->builder, $exported->get('ids'));
        $this->messages->success(trans('streams::message.delete_success', $exported));
    }
}
