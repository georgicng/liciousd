<?php

return [
    'admin' => [
        'system' => [
            'pickup'          => 'Picking up at the store',
            'display_address' => 'Show the pick-up address in the checkout form'
        ],
        'components' => [
            'layouts' => [
                'sidebar' => [
                    'cs' => 'Shipping Cities'
                ]
            ]
        ],
        'settings' => [
            'cs' => [
                'index' => [
                    'title'      => 'Shipping Cities',
                    'locale'     => 'City',
                    'create-btn' => 'Add City',
                    'logo-size'  => 'Image resolution should be like 24px X 16px',

                    'datagrid' => [
                        'actions'   => 'Actions',
                        'id'        => 'ID',
                        'name'      => 'Name',
                        'rate'      => 'Rate',
                        'status'        => 'Status',
                        'inactive' => 'Disabled',
                        'active' => 'Enabled',
                        'draft' => 'Draft',
                        'edit'      => 'Edit',
                        'delete'    => 'Delete',
                    ],

                    'create' => [
                        'rate'             => 'Rate',
                        'name'             => 'Name',
                        'status'        => 'Status',
                        'locale-logo'      => 'City Logo',
                        'title'            => 'Add City',
                        'save-btn'         => 'Save City',
                        'select-direction' => 'Select Direction',
                    ],

                    'edit' => [
                        'title' => 'Edit Citys',
                    ],

                    'create-success'    => 'City created successfully.',
                    'update-success'    => 'City updated successfully.',
                    'delete-success'    => 'City deleted successfully.',
                    'last-delete-error' => 'At least one City is required.',
                    'delete-warning'    => 'Are you sure, you want to perform this action?',
                    'delete-failed'     => 'City deletion failed',
                ],
            ],
        ]

    ]
];
