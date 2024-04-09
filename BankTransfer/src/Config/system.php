<?php

return [
    [
        'key'    => 'sales.payment_methods.bank_transfer',
        'name'   => 'Bank Transfer',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.admin.system.title',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.admin.system.description',
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'account',
                'title'         => 'admin::app.admin.system.account',
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'additional',
                'title'         => 'admin::app.admin.system.additional',
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true,
            ]
        ]
    ]
];
