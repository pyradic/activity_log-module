<?php namespace Pyro\ActivityLogModule\Activity\Table;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Anomaly\Streams\Platform\Entry\EntryModel;
use Anomaly\Streams\Platform\Entry\EntryQueryBuilder;
use Anomaly\Streams\Platform\Ui\Table\Component\View\Query\RecentlyCreatedQuery;
use Anomaly\Streams\Platform\Ui\Table\TableBuilder;
use Anomaly\UsersModule\User\Contract\UserInterface;
use Illuminate\Support\Str;
use Pyro\ActivityLogModule\Activity\ActivityModel;
use Pyro\ActivityLogModule\Activity\Contract\Activity;

/**
 * 
 *
 */
class ActivityTableBuilder extends TableBuilder
{

    protected $filters = [
        'description',
        'created_by_id',
    ];

    protected $options = [
        'class' => 'table table-pyro-activity',
    ];

    protected $assets = [
        'styles.css' => [ 'pyro.module.activity_log::css/activity-table-builder.css' ],
    ];

    protected $buttons = [
        'edit',
    ];

    protected $actions = [
        'delete',
        'backup'     => [
            'handler' => ActivityTableBackupHandler::class,
            'button'  => 'info',
            'icon'    => 'download',
            'text'    => 'Backup',
        ],
        'clean'      => [
            'handler' => ActivityTableCleanHandler::class,
            'button'  => 'warning',
            'icon'    => 'download',
            'text'    => 'Clean',
        ],
        'backup_all' => [
            'handler'  => ActivityTableBackupAllHandler::class,
            'button'   => 'info',
            'icon'     => 'download',
            'text'     => 'Backup All',
            'enabled'  => true,
            'disabled' => false,
        ],
        'clean_all'  => [
            'handler'  => ActivityTableCleanAllHandler::class,
            'button'   => 'warning',
            'icon'     => 'download',
            'text'     => 'Clean All',
            'enabled'  => true,
            'disabled' => false,
        ],
    ];

    public function onReady(TableBuilder $builder)
    {
        $this->on('queried', function (TableBuilder $builder, EntryQueryBuilder $query) {
            $query->with([ 'subject', 'causer' ]);
        });
        $builder->setViews([
            'all'       => [
                'query' => RecentlyCreatedQuery::class,
            ],
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
        $builder->setColumns([
            'causer'      => [
                'heading'     => 'Door',
                'wrapper'     => function (Activity $entry) {
                    if ($entry->causer instanceof UserInterface) {
                        return $entry->causer->getPresenter()->link();
                    }
                    if ($entry->causer instanceof EntryInterface) {
                        $titleColumn = $entry->causer->getStream()->getTitleColumn();
                        return $entry->causer[ $titleColumn ];
                    }
                    return $entry->causer->getId();
                },
                'sort_column' => 'created_by_id',
                'attributes'  => [
                    'style' => 'width: 150px',
                ],
            ],
//            'created_by'  => [
//                'wrapper'     => function (EntryModel $entry) {
//                    if ($entry->created_by instanceof UserInterface) {
//                        return $entry->createdBy->getPresenter()->link();
//                        return "<a href='/admin/users/edit/{$entry->created_by->id}'>{$entry->created_by->username}</a>";
//                    }
//                    return ' ';
//                },
//                'sort_column' => 'created_by_id',
//                'attributes'  => [
//                    'style' => 'width: 150px',
//                ],
//            ],
            'subject'     => [
                'attributes' => [ 'width' => '300px' ],
                'value'      => function (Activity $entry) {
                    if ($entry->properties->has('subject')) {
                        return $entry->properties[ 'subject' ];
                    }
                    if ($entry->subject instanceof EntryInterface) {
                        $stream      = $entry->subject->getStream();
                        $titleColumn = $stream->getTitleColumn();
                        $streamName  = trans($stream->getName());
                        $subjectName = Str::truncate($entry->subject[ $titleColumn ], 27, '..');
                        return "{$streamName} :: {$subjectName}";
                    }
                },
            ],
            'description' => [
                'is_safe'     => true,
                'wrapper'     => function (ActivityModel $entry) {
                    $entry->loadMissing([ 'subject', 'causer' ]);
                    return $entry->description;
                },
                'sort_column' => 'subject_id',
            ],
            'created_at'  => [
                'value'      => 'entry.created_at.format("d-m-Y h:i:s")',
                'attributes' => [
                    'style' => 'width: 150px',
                ],
            ],
        ]);
    }

}
