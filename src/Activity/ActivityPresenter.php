<?php namespace Pyro\ActivityLogModule\Activity;

use Anomaly\Streams\Platform\Entry\EntryPresenter;
use Anomaly\UsersModule\User\Contract\UserInterface;
use Pyro\Platform\Entry\EntryModel;

/**
 * 
 *
 * @property \Pyro\ActivityLogModule\Activity\ActivityModel $object
 * @method \Pyro\ActivityLogModule\Activity\ActivityModel getObject()
 * @mixin \Pyro\ActivityLogModule\Activity\ActivityModel
 * @property \Anomaly\TextFieldType\TextFieldTypePresenter $log_name
 * @property \Anomaly\TextareaFieldType\TextareaFieldTypePresenter $description
 * @property \Anomaly\IntegerFieldType\IntegerFieldTypePresenter $subject_id
 * @property \Anomaly\TextFieldType\TextFieldTypePresenter $subject_type
 * @property \Anomaly\IntegerFieldType\IntegerFieldTypePresenter $causer_id
 * @property \Anomaly\TextFieldType\TextFieldTypePresenter $causer_type
 * @property \Anomaly\TextareaFieldType\TextareaFieldTypePresenter $properties
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
