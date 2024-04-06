<?php

return
  [
    'key' => 'sales.carriers.pickup',
    'name' => 'pickup::app.admin.system.pickup',
    'sort' => 3,
    'fields' => [
      [
        'name'          => 'title',
        'title'         => 'pickup::app.admin.system.title',
        'type'          => 'text',
        'channel_based' => true,
        'locale_based'  => true
      ], [
        'name'          => 'description',
        'title'         => 'pickup::app.admin.system.description',
        'type'          => 'textarea',
        'channel_based' => true,
        'locale_based'  => true
      ], [
        'name'          => 'display_address',
        'title'         => 'pickup::app.admin.system.display_address',
        'type'          => 'boolean',
        'channel_based' => true,
        'locale_based'  => false,
      ], [
        'name'          => 'active',
        'title'         => 'pickup::app.admin.system.status',
        'type'          => 'boolean',
        'channel_based' => true,
        'locale_based'  => false,
      ]
    ]
];
