<?php namespace Pyro\ActivityLogModule\Activity;

use Anomaly\Streams\Platform\Entry\EntryPresenter;
use Pyro\Platform\Entry\EntryModel;

/**
 * 
 *
 * @mixin \Pyro\ActivityLogModule\Activity\ActivityModel
 * @mixin \Pyro\ActivityLogModule\Activity\ActivityModel
 * @mixin \Pyro\ActivityLogModule\Activity\ActivityModel
 * @mixin  \Pyro\ActivityLogModule\Activity\ActivityModel
 * @property  \Pyro\ActivityLogModule\Activity\ActivityModel $object
 */
class ActivityPresenter extends EntryPresenter
{
    public function userEmail()
    {

//        /** @var \Anomaly\UsersModule\User\Contract\UserInterface $user */
//        $user=$this->causer->first();
//        if(!$user instanceof UserInterface){
//            return '';
//        }
        return (string)$this->createdBy->email;
    }

    public function causerTitle()
    {
        $title = '';
        if ($titleColumn = $this->getTitleColumn($this->causer_type)) {
            $title = $this->causer->first()->{$titleColumn};
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
            $title = $this->subject->first()->{$titleColumn};
        }
        return $title;
    }
}
