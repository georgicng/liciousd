<?php

return [
    [
        'key'    => 'sales.payment_methods.paystack_redirect',
        'name'   => 'Paystack Standard',
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
                'name'          => 'sandbox',
                'title'         => 'paystack::app.admin.system.sandbox',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ],
        ]
    ],
    [
        'key'    => 'sales.payment_methods.paystack_popup',
        'name'   => 'Paystack Popup',
        'sort'   => 5,
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
            ],[
                'name'          => 'sandbox',
                'title'         => 'paystack::app.admin.system.sandbox',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ],
        ]
    ]
];
