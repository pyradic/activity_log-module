<?php namespace Pyro\ActivityLogModule\Activity\Table;

use Anomaly\Streams\Platform\Entry\EntryModel;
use Anomaly\Streams\Platform\Ui\Table\TableBuilder;
use Anomaly\UsersModule\User\Contract\UserInterface;

class ActivityTableColumns extends TableBuilder
{
    public function handle(ActivityTableBuilder $builder)
    {
        $builder->setColumns([
            'created_by' => [
                'wrapper'     => function (EntryModel $entry) {
                    if ($entry->created_by instanceof UserInterface) {
                        return "<a href='/admin/users/edit/{$entry->created_by->id}'>{$entry->created_by->username}</a>";
                    }
                    return ' ';
                },
                'sort_column' => 'created_by_id',
            ],
            'created_at' => 'entry.created_at.format("d-m-Y h:i:s")',
            'description',
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
                'wrapper'     => function (EntryModel $entry) {
                    $value = ' ';
                    if ($entry->subject instanceof EntryModel && $titleColumn = $entry->subject->getStream()->getTitleColumn()) {
                        $subject   = $entry->subject;
                        $stream    = $subject->getStream();
                        $name      = $stream->getName();
                        $namespace = $stream->getNamespace();
                        $title     = $subject->getAttribute($titleColumn);
                        $value     = trans()->has($name) ? trans($name) : $namespace;
                        $value     .= ' - ' . $title;
                    }
                    return $value;
                },
                'sort_column' => 'subject_id',
            ],
        ]);
    }

}
