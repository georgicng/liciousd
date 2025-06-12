<?php
return [
    [
        'key'    => 'sales.order_settings.store',
        'name'   => 'licious::app.store.name',
        'info'   => 'licious::app.store.info',
        'sort'   => 4,
        'fields' => [
            [
                'name'          => 'helpline',
                'title'         => 'licious::app.store.helpline',
                'type'          => 'text',
                'channel_based' => false,
            ],
        ],
    ],
    [
        'key'  => 'store',
        'name' => 'licious::app.store.information.title',
        'info' => 'licious::app.store.information.description',
        'sort' => 8
    ],
    [
        'key'  => 'store.information',
        'name' => 'licious::app.store.settings.information.title',
        'info' => 'licious::app.store.settings.information.description',
        'icon' => 'settings/settings.svg',
        'sort' => 1,
    ],
    [
        'key'    => 'store.information.bio',
        'name'   => 'licious::app.store.settings.information.bio.title',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'image',
                'title'         => 'licious::app.store.settings.information.bio.logo',
                'type'          => 'image',
                'info'          => 'licious::app.store.settings.information.bio.logo-information',
                'channel_based' => true,
                'locale_based'  => false,
                'validation'    => 'mimes:bmp,jpeg,jpg,png,webp',
            ],
            [
                'name'  => 'bio',
                'title' => 'licious::app.store.settings.information.bio.description',
                'type'  => 'textarea'
            ],
            [
                'name'  => 'address',
                'title' => 'licious::app.store.settings.information.bio.address',
                'type'  => 'textarea'
            ],
            [
                'name'  => 'email',
                'title' => 'licious::app.store.settings.information.bio.email',
                'type'  => 'text'
            ],
            [
                'name'  => 'phone',
                'title' => 'licious::app.store.settings.information.bio.phone',
                'type'  => 'text'
            ]
        ]
    ],
    [
        'key'    => 'store.information.socials',
        'name'   => 'licious::app.store.settings.information.socials.title',
        'sort'   => 2,
        'fields' => [
            [
                'name'  => 'instagram',
                'title' => 'licious::app.store.settings.information.socials.instagram',
                 'type'   =>  'text',
                 'validation'=> ''
             ],
             [
                 'name'   =>  'twitter',
                  'title' =>  'licious::app.store.settings.information.socials.twitter',
                  'type'  =>  'text',
                  'validation' => ''
             ],
             [
                 'name'  => 'facebook',
                 'title' => 'licious::app.store.settings.information.socials.facebook',
                 'type'  => 'text',
                 'validation' => ''
             ],
             [
                 'name'  => 'youtube',
                 'title' => 'licious::app.store.settings.information.socials.youtube',
                 'type'  => 'text',
                 'validation' => ''
             ],
             [
                 'name'  => 'tiktok',
                 'title' => 'licious::app.store.settings.information.socials.tiktok',
                  'type'   =>  'text',
                  'validation' => ''
            ]
        ]
    ],
    [
        'key'    => 'store.information.copyright',
        'name'    =>  'licious::app.store.settings.information.copyright.title',
        'sort'   => 3,
        'fields' => [
            [
                'name'  => 'copyright',
                'title' => 'licious::app.store.settings.information.copyright.text',
                'type'  => 'text'
            ],
        ]
    ],

];
