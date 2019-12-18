<?php

use Anomaly\Streams\Platform\Database\Migration\Migration;

class PyroModuleActivityLogCreateActivityStream extends Migration
{

    /**
     * This migration creates the stream.
     * It should be deleted on rollback.
     *
     * @var bool
     */
    protected $delete = true;

    /**
     * The stream definition.
     *
     * @var array
     */
    protected $stream = [
        'slug'         => 'activity',
        'title_column' => 'id',
        'translatable' => false,
        'versionable'  => false,
        'trashable'    => false,
        'searchable'   => true,
        'sortable'     => false,
    ];

    /**
     * The stream assignments.
     *
     * @var array
     */
    protected $assignments = [
        'log_name'=> [ 'required' => true, ],
        'description' => [ 'required' => true, ],
        'subject_id',
        'subject_type',
        'causer_id',
        'causer_type',
        'properties',
    ];

}
