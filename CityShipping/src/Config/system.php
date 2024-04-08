<?php

return
    [[
        'key'    => 'sales.carriers.cs',
        'name'   => 'cs::app.admin.system.cs',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'cs::app.admin.system.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true
            ], [
                'name'          => 'description',
                'title'         => 'cs::app.admin.system.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => false
            ], [
                'name'          => 'default_rate',
                'title'         => 'cs::app.admin.system.rate',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false
            ], [
                'name'          => 'base_amount',
                'title'         => 'cs::app.admin.system.minimum-amount',
                'type'          => 'text',
                'channel_based' => true,
                'locale_based'  => false
            ], [
                'name'    => 'type',
                'title'   => 'cs::app.admin.system.type',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'Per Unit',
                        'value' => 'per_unit',
                    ], [
                        'title' => 'Per Order',
                        'value' => 'per_order',
                    ],
                ],
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'active',
                'title'         => 'cs::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false
            ]
        ]
    ]];
