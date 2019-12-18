<?php namespace Pyro\ActivityLogModule\Activity;

use Pyro\ActivityLogModule\Activity\Contract\ActivityRepositoryInterface;
use Anomaly\Streams\Platform\Entry\EntryRepository;

/**
 * 
 *
 * @method \Pyro\ActivityLogModule\Activity\ActivityCollection|\Pyro\ActivityLogModule\Activity\Contract\ActivityInterface[] all() 
 * @method \Pyro\ActivityLogModule\Activity\ActivityCollection|\Pyro\ActivityLogModule\Activity\Contract\ActivityInterface[] allWithTrashed() 
 * @method \Pyro\ActivityLogModule\Activity\ActivityCollection|\Pyro\ActivityLogModule\Activity\Contract\ActivityInterface[] allWithoutRelations() 
 * @method \Pyro\ActivityLogModule\Activity\Contract\ActivityInterface first($direction = "asc") 
 * @method \Pyro\ActivityLogModule\Activity\Contract\ActivityInterface find($id) 
 * @method \Pyro\ActivityLogModule\Activity\Contract\ActivityInterface findWithoutRelations($id) 
 * @method \Pyro\ActivityLogModule\Activity\Contract\ActivityInterface findBy($key, $value) 
 * @method \Pyro\ActivityLogModule\Activity\ActivityCollection|\Pyro\ActivityLogModule\Activity\Contract\ActivityInterface[] findAll(array $ids) 
 * @method \Pyro\ActivityLogModule\Activity\ActivityCollection|\Pyro\ActivityLogModule\Activity\Contract\ActivityInterface[] findAllBy(string $key, $value) 
 * @method \Pyro\ActivityLogModule\Activity\Contract\ActivityInterface findTrashed($id) 
 * @method \Anomaly\Streams\Platform\Entry\EntryQueryBuilder newQuery() 
 * @method \Pyro\ActivityLogModule\Activity\Contract\ActivityInterface update(array $attributes = ['log_name' => '','description' => '','subject_id' => '','subject_type' => '','causer_id' => '','causer_type' => '','properties' => '',]) 
 * @method \Pyro\ActivityLogModule\Activity\ActivityModel getModel() 
 * @method \Pyro\ActivityLogModule\Activity\Contract\ActivityInterface newInstance(array $attributes = []) 
 * @method \Pyro\ActivityLogModule\Activity\ActivityCollection|\Pyro\ActivityLogModule\Activity\Contract\ActivityInterface[] sorted($direction = "asc") 
 * @method \Pyro\ActivityLogModule\Activity\Contract\ActivityInterface lastModified() 
 */
class ActivityRepository extends EntryRepository implements ActivityRepositoryInterface
{

    /**
     * The entry model.
     *
     * @var ActivityModel
     */
    protected $model;

    /**
     * Create a new ActivityRepository instance.
     *
     * @param ActivityModel $model
     */
    public function __construct(ActivityModel $model)
    {
        $this->model = $model;
    }
}
