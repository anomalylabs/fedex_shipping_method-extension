<?php

use Anomaly\Streams\Platform\Database\Migration\Migration;

class AnomalyExtensionFedexShippingMethodCreateFedexShippingMethodFields extends Migration
{

    /**
     * The addon fields.
     *
     * @var array
     */
    protected $fields = [
        'name' => 'anomaly.field_type.text',
        'slug' => [
            'type' => 'anomaly.field_type.text',
            'config' => [
                'slugify' => 'name',
                'type' => '_'
            ],
        ],
    ];

}
