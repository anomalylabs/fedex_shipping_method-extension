<?php

return [
    'service' => [
        'required' => true,
        'type'     => 'anomaly.field_type.select',
        'config'   => [
            'options' => [
                'anomaly.extension.fedex_shipping_method::configuration.service.domestic'      => [
                    'FEDEX_GROUND' => 'anomaly.extension.fedex_shipping_method::configuration.service.option.FEDEX_GROUND',
                    'FEDEX_2_DAY'  => 'anomaly.extension.fedex_shipping_method::configuration.service.option.FEDEX_2_DAY',
                    //'FEDEX_2_DAY_AM'                      => 'anomaly.extension.fedex_shipping_method::configuration.service.option.FEDEX_2_DAY_AM',
                    //'FEDEX_EXPRESS_SAVER'                 => 'anomaly.extension.fedex_shipping_method::configuration.service.option.FEDEX_EXPRESS_SAVER',
                    //'STANDARD_OVERNIGHT'                  => 'anomaly.extension.fedex_shipping_method::configuration.service.option.STANDARD_OVERNIGHT',
                    //'FIRST_OVERNIGHT'                     => 'anomaly.extension.fedex_shipping_method::configuration.service.option.FIRST_OVERNIGHT',
                    //'PRIORITY_OVERNIGHT'                  => 'anomaly.extension.fedex_shipping_method::configuration.service.option.PRIORITY_OVERNIGHT',
                    //'GROUND_HOME_DELIVERY'                => 'anomaly.extension.fedex_shipping_method::configuration.service.option.GROUND_HOME_DELIVERY',
                    //'SMART_POST'                          => 'anomaly.extension.fedex_shipping_method::configuration.service.option.SMART_POST',
                    //'FEDEX_FREIGHT_ECONOMY'               => 'anomaly.extension.fedex_shipping_method::configuration.service.option.FEDEX_FREIGHT_ECONOMY',
                    //'FEDEX_FREIGHT_PRIORITY'              => 'anomaly.extension.fedex_shipping_method::configuration.service.option.FEDEX_FREIGHT_PRIORITY',
                    //'FEDEX_1_DAY_FREIGHT'                 => 'anomaly.extension.fedex_shipping_method::configuration.service.option.FEDEX_1_DAY_FREIGHT',
                    //'FEDEX_2_DAY_FREIGHT'                 => 'anomaly.extension.fedex_shipping_method::configuration.service.option.FEDEX_2_DAY_FREIGHT',
                    //'FEDEX_3_DAY_FREIGHT'                 => 'anomaly.extension.fedex_shipping_method::configuration.service.option.FEDEX_3_DAY_FREIGHT',
                    //'FEDEX_FIRST_FREIGHT'                 => 'anomaly.extension.fedex_shipping_method::configuration.service.option.FEDEX_FIRST_FREIGHT',
                ],
                'anomaly.extension.fedex_shipping_method::configuration.service.international' => [
                    'INTERNATIONAL_ECONOMY'          => 'anomaly.extension.fedex_shipping_method::configuration.service.option.INTERNATIONAL_ECONOMY',
                    'INTERNATIONAL_FIRST'            => 'anomaly.extension.fedex_shipping_method::configuration.service.option.INTERNATIONAL_FIRST',
                    'INTERNATIONAL_PRIORITY'         => 'anomaly.extension.fedex_shipping_method::configuration.service.option.INTERNATIONAL_PRIORITY',
                    'INTERNATIONAL_ECONOMY_FREIGHT'  => 'anomaly.extension.fedex_shipping_method::configuration.service.option.INTERNATIONAL_ECONOMY_FREIGHT',
                    'INTERNATIONAL_PRIORITY_FREIGHT' => 'anomaly.extension.fedex_shipping_method::configuration.service.option.INTERNATIONAL_PRIORITY_FREIGHT',
                ],
            ],
        ],
    ],
];
