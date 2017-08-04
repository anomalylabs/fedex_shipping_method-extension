<?php

return [
    'account_number' => [
        'required' => true,
        'env'      => 'FEDEX_ACCOUNT_NUMBER',
        'bind'     => 'anomaly.extension.fedex_shipping_method::config.account_number',
        'type'     => 'anomaly.field_type.encrypted',
    ],
    'meter_number'   => [
        'required' => true,
        'env'      => 'FEDEX_METER_NUMBER',
        'bind'     => 'anomaly.extension.fedex_shipping_method::config.meter_number',
        'type'     => 'anomaly.field_type.encrypted',
    ],
    'access_key'     => [
        'required' => true,
        'env'      => 'FEDEX_ACCESS_KEY',
        'bind'     => 'anomaly.extension.fedex_shipping_method::config.access_key',
        'type'     => 'anomaly.field_type.encrypted',
    ],
    'password'       => [
        'required' => true,
        'env'      => 'FEDEX_PASSWORD',
        'bind'     => 'anomaly.extension.fedex_shipping_method::config.password',
        'type'     => 'anomaly.field_type.encrypted',
    ],
];
