<?php

return [
    'access_key' => [
        'env'  => 'FEDEX_ACCESS_KEY',
        'bind' => 'anomaly.extension.fedex_shipping_method::config.access_key',
        'type' => 'anomaly.field_type.encrypted',
    ],
    'password'   => [
        'env'  => 'FEDEX_PASSWORD',
        'bind' => 'anomaly.extension.fedex_shipping_method::config.password',
        'type' => 'anomaly.field_type.encrypted',
    ],
];
