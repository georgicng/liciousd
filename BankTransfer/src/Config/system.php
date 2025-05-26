<?php

return [
    [
        'key'    => 'sales.payment_methods.bank_transfer',
        'name'   => 'Bank Transfer',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'bank::app.admin.system.title',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'bank::app.admin.system.description',
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'active',
                'title'         => 'bank::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'account',
                'title'         => 'bank::app.admin.system.account',
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'additional',
                'title'         => 'bank::app.admin.system.additional',
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'image',
                'title'         => 'bank::app.admin.system.logo',
                'type'          => 'image',
                'info'          => 'bank::app.admin.system.logo-information',
                'channel_based' => true,
                'locale_based'  => false,
                'validation'    => 'mimes:bmp,jpeg,jpg,png,webp',
            ]
        ]
    ]
];
