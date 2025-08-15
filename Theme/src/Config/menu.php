<?php

return [
    [
        'key'   => 'account',
        'name'  => 'licious::app.layouts.my-account',
        'route' => 'shop.customers.account.profile.index',
        'icon'  => '',
        'sort'  => 1,
    ], [
        'key'   => 'account.profile',
        'name'  => 'licious::app.layouts.profile',
        'route' => 'shop.customers.account.profile.index',
        'icon'  => 'ri-user-3-line',
        'sort'  => 1,
    ], [
        'key'   => 'account.address',
        'name'  => 'licious::app.layouts.address',
        'route' => 'shop.customers.account.addresses.index',
        'icon'  => 'ri-map-pin-line',
        'sort'  => 2,
    ], [
        'key'   => 'account.orders',
        'name'  => 'licious::app.layouts.orders',
        'route' => 'shop.customers.account.orders.index',
        'icon'  => 'ri-shopping-bag-line',
        'sort'  => 3,
    ], [
        'key'   => 'account.downloadables',
        'name'  => 'licious::app.layouts.downloadable-products',
        'route' => 'shop.customers.account.downloadable_products.index',
        'icon'  => 'ri-arrow-down-line',
        'sort'  => 4,
    ], [
        'key'   => 'account.reviews',
        'name'  => 'licious::app.layouts.reviews',
        'route' => 'shop.customers.account.reviews.index',
        'icon'  => 'ri-star-line',
        'sort'  => 5,
    ], [
        'key'   => 'account.wishlist',
        'name'  => 'licious::app.layouts.wishlist',
        'route' => 'shop.customers.account.wishlist.index',
        'icon'  => 'ri-heart-line',
        'sort'  => 6,
    ],
];
