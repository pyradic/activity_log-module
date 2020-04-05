<?php

namespace Pyro\ActivityLogModule\Activity\Table;

use Anomaly\Streams\Platform\Ui\Table\Component\Action\Handler\Delete;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueuedDelete implements ShouldQueue
{
    /** @var array */
    protected $ids;

    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    public function handle(ActivityTableBuilder $builder)
    {
        resolve(Delete::class)->handle($builder, $this->ids);
    }
}
