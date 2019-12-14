<?php

use Anomaly\Streams\Platform\Database\Migration\Migration;

class PyroModuleActivityLogCreateActivityLogFields extends Migration
{

    /**
     * The addon fields.
     *
     * @var array
     */
    protected $fields = [
        'log_name'     => 'anomaly.field_type.text',
        'description'  => 'anomaly.field_type.textarea', //text
        'subject_id'   => 'anomaly.field_type.integer', //unsignedBigInteger
        'subject_type' => 'anomaly.field_type.text', //string
        'causer_id'    => 'anomaly.field_type.integer', //unsignedBigInteger
        'causer_type'  => 'anomaly.field_type.text', //string
        'properties'   => 'anomaly.field_type.textarea', //json
    ];


}
