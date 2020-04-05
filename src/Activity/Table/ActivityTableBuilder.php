<?php namespace Pyro\ActivityLogModule\Activity\Table;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Anomaly\Streams\Platform\Entry\EntryModel;
use Anomaly\Streams\Platform\Entry\EntryQueryBuilder;
use Anomaly\Streams\Platform\Ui\Table\Component\Action\Handler\Export;
use Anomaly\Streams\Platform\Ui\Table\TableBuilder;
use Anomaly\UsersModule\User\Contract\UserInterface;
use Pyro\ActivityLogModule\Activity\ActivityModel;

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

    protected $buttons = [
        'edit',
    ];

    protected $actions = [
        'delete',
        'backup' => [
            'handler' => ActivityTableBackupHandler::class,
            'button'  => 'info',
            'icon'    => 'download',
            'text'    => 'Backup',
        ],
        'clean' => [
            'handler' => ActivityTableCleanHandler::class,
            'button'  => 'warning',
            'icon'    => 'download',
            'text'    => 'Clean',
        ],
        'backup_all' => [
            'handler' => ActivityTableBackupAllHandler::class,
            'button'  => 'info',
            'icon'    => 'download',
            'text'    => 'Backup All',
            'enabled' => true,
            'disabled' => false
        ],
        'clean_all' => [
            'handler' => ActivityTableCleanAllHandler::class,
            'button'  => 'warning',
            'icon'    => 'download',
            'text'    => 'Clean All',
            'enabled' => true,
            'disabled' => false
        ],
    ];

    protected $options = [];


    public function onReady(TableBuilder $builder)
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
        $builder->setColumns([
            'created_by' => [
                'wrapper'     => function (EntryModel $entry) {
                    if ($entry->created_by instanceof UserInterface) {
                        return "<a href='/admin/users/edit/{$entry->created_by->id}'>{$entry->created_by->username}</a>";
                    }
                    return ' ';
                },
                'sort_column' => 'created_by_id',
                'attributes'  => [
                    'style' => 'width: 150px',
                ],
            ],
            'created_at' => [
                'value'      => 'entry.created_at.format("d-m-Y h:i:s")',
                'attributes' => [
                    'style' => 'width: 150px',
                ],
            ],
//            'causer',//=> [ 'value' => '{entry.causerTitle' ],
//            'causer'     => [
//                'wrapper'     => function (EntryModel $entry) use ($builder) {
//                    $value = ' ';
//                    if ($entry->causer instanceof EntryModel && $titleColumn = $entry->causer->getStream()->getTitleColumn()) {
//                        $causer    = $entry->causer;
//                        $stream    = $causer->getStream();
//                        $name      = $stream->getName();
//                        $namespace = $stream->getNamespace();
//                        $title     = $causer->getAttribute($titleColumn);
//                        $value     = trans()->has($name) ? trans($name) : $namespace;
//                        $value     .= ' - ' . $title;
//                    }
//                    return $value;
//                },
//                'sort_column' => 'causer_id',
//            ],
//            'subject',//=> [ 'value' => 'entry.subjectTitle' ],
            'subject'    => [
                'wrapper'     => function (ActivityModel $entry) {
                    $value = ' ';
                    if ($entry->description) {
                        $value = $entry->description;
                    }

                    $subject = null;
                    if ($entry->subject instanceof EntryInterface) {
                        $subject = $entry->subject;
                    } elseif(class_exists($entry->subject_type)) {
                        $class = $entry->subject_type;
                        $subject = new $class;
                    }
                    if($subject){
                        $stream    = $subject->getStream();
                        $name      = $stream->getName();
                        $namespace = $stream->getNamespace();
                        $value     .= ': ';
                        $value     .= trans()->has($name) ? trans($name) : $namespace;

                        if($titleColumn = $subject->getStream()->getTitleColumn()) {
                            $value     .= ' - ' . $subject->getAttribute($titleColumn);
                        }
                    }
                    return $value;
                },
                'sort_column' => 'subject_id',
            ],
        ]);
    }

}
