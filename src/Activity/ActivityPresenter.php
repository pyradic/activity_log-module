<?php namespace Pyro\ActivityLogModule\Activity;

use Anomaly\Streams\Platform\Entry\EntryPresenter;
use Anomaly\UsersModule\User\Contract\UserInterface;
use Pyro\Platform\Entry\EntryModel;

/**
 * 
 *
 * @mixin \Pyro\ActivityLogModule\Activity\ActivityModel
 * @property \Pyro\ActivityLogModule\Activity\ActivityModel $object
 */
class ActivityPresenter extends EntryPresenter
{
    public function createdByUser()
    {
        $this->load('created_by');
        $user = $this->created_by;
        if ( ! $user instanceof UserInterface) {
            return '';
        }
        $html="<a href='/admin/users/edit/{$user->id}'>{$user->username}</a>";
        return $html;
    }

    public function causerTitle()
    {
        $title = '';
        if ($titleColumn = $this->getTitleColumn($this->causer_type)) {
            $title = $this->causer->{$titleColumn};
        }
        return $title;
    }

    protected function getTitleColumn($field)
    {
        $type = (string)$field;
        if ($type && class_exists($type) && in_array(EntryModel::class, class_parents($type))) {

            /** @var \Pyro\Platform\Entry\EntryModel $s */
            $s = new $type;
            /** @var  $stream */
            $stream = $s->getStream();
            return $stream->getTitleColumn();
        }
    }

    public function subjectTitle()
    {
        $title = '';
        if ($titleColumn = $this->getTitleColumn($this->subject_type)) {
            $title = $this->subject->first()->getAttribute($titleColumn);
        }
        return $title;
    }
}
