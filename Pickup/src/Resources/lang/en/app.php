<?php

return [
    'admin' => [
        'system' => [
            'pickup' => 'Store Pickup',
            'title' => 'Title',
            'description' => 'Description',
            'rate' => 'Base Rate',
            'minimum-amount' => 'Minimum order to qualify',
            'status' => 'Status',
            'type' => 'Type',
            'display_address' => 'Display Address'
        ],
        'components' => [
            'layouts' => [
                'sidebar' => [
                    'pickup' => 'Pickup Centres'
                ]
            ]
        ],
        'settings' => [
            'pickup' => [
                'index' => [
                    'title'      => 'Picku Centres',
                    'Centre'     => 'Centre',
                    'create-btn' => 'Add Centre',
                    'logo-size'  => 'Image resolution should be like 24px X 16px',

                    'datagrid' => [
                        'actions'   => 'Actions',
                        'id'        => 'ID',
                        'name'      => 'Name',
                        'city'      => 'City',
                        'rate'      => 'Rate',
                        'status'    => 'Status',
                        'edit'      => 'Edit',
                        'delete'    => 'Delete',
                        'inactive' => 'Disabled',
                        'active' => 'Enabled',
                        'draft' => 'Draft',
                    ],

                    'create' => [
                        'rate'             => 'Rate',
                        'name'             => 'Name',
                        'address'           => 'Address',
                        'phone'            => 'Phone',
                        'landmark'          => "Nearest Landmark",
                        'status'          => "Status",
                        'whatsapp'          => "Whatsapp Number",
                        'email'          => "Email",
                        'location'          => "Map Link",
                        'additional'          => "Opening Times",
                        'city'          => "City",
                        'title'            => 'Create Centre',
                        'save-btn'         => 'Save Centre',
                        'select-direction' => 'Select Direction',
                    ],

                    'edit' => [
                        'title' => 'Edit Centre',
                    ],

                    'create-success'    => 'Centre created successfully.',
                    'update-success'    => 'Centre updated successfully.',
                    'delete-success'    => 'Centre deleted successfully.',
                    'last-delete-error' => 'At least one Centre is required.',
                    'delete-warning'    => 'Are you sure, you want to perform this action?',
                    'delete-failed'     => 'Centre deletion failed',
                ],
            ],
        ],
    ]
];
