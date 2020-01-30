<?php namespace Pyro\ActivityLogModule\Activity\Table;

use Anomaly\Streams\Platform\Entry\EntryQueryBuilder;
use Anomaly\Streams\Platform\Ui\Table\TableBuilder;

class ActivityTableViews extends TableBuilder
{
    public function handle(ActivityTableBuilder $builder)
    {
        $builder->setViews([
            'all',
            'by_user'   => [
                'query'   => function (EntryQueryBuilder $query) {
                    $query->whereNotNull('created_by_id');
                    return;
                },
                'columns' => [
                    'created_by',
                    'created_at',
                    'description',
                    'subject',
                ],
            ],
            'by_system' => [
                'query'   => function (EntryQueryBuilder $query) {
                    $query->whereNull('created_by_id');
                    return;
                },
                'columns' => [
                    'created_at',
                    'description',
                    'subject',
                ],
            ],
        ]);
    }

}
