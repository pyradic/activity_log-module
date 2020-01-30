<?php namespace Pyro\ActivityLogModule\Activity\Table;

use Anomaly\Streams\Platform\Ui\Table\TableBuilder;

class ActivityTableBuilder extends TableBuilder
{

    protected $filters = [
        'description',
        'created_by_id',
    ];

    protected $buttons = [
        'edit',
    ];

    protected $actions = [
        'delete',
    ];

    protected $options = [];

}
