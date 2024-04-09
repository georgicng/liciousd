<?php

return [
    [
        'key'    => 'sales.payment_methods.paystack',
        'name'   => 'Paystack',
        'sort'   => 4,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'paystack::app.admin.system.title',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'paystack::app.admin.system.description',
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'active',
                'title'         => 'paystack::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'base_url',
                'title'         => 'paystack::app.admin.system.base_url',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'mode',
                'title'         => 'paystack::app.admin.system.mode',
                'type'          => 'select',
                'options'       => [
                    ['value' => 'test', 'title' => 'Test'],
                    ['value' => 'live', 'title' => 'Live']
                ],
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'test_private_key',
                'title'         => 'paystack::app.admin.system.test_private_key',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'test_public_key',
                'title'         => 'paystack::app.admin.system.test_public_key',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'live_private_key',
                'title'         => 'paystack::app.admin.system.live_private_key',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'live_public_key',
                'title'         => 'paystack::app.admin.system.live_public_key',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ]
        ]
    ]
];
