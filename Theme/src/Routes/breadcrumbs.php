<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

/**
 * Profile routes.
 */
Breadcrumbs::for('licious.customer.profile.index', function (BreadcrumbTrail $trail) {
    $trail->push(trans('shop::app.customer.account.profile.index.title'), route('licious.customer.profile.index'));
});

Breadcrumbs::for('licious.customer.profile.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('licious.customer.profile.index');
});

/**
 * Order routes.
 */
Breadcrumbs::for('licious.customer.orders.index', function (BreadcrumbTrail $trail) {
    $trail->parent('licious.customer.profile.index');

    $trail->push(trans('shop::app.customer.account.order.index.page-title'), route('licious.customer.orders.index'));
});

Breadcrumbs::for('licious.customer.orders.view', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('licious.customer.orders.index');
});

/**
 * Downloadable products.
 */
Breadcrumbs::for('licious.customer.downloadable_products.index', function (BreadcrumbTrail $trail) {
    $trail->parent('licious.customer.profile.index');

    $trail->push(trans('shop::app.customer.account.downloadable_products.title'), route('licious.customer.downloadable_products.index'));
});

/**
 * Wishlists.
 */
Breadcrumbs::for('licious.customer.wishlist.index', function (BreadcrumbTrail $trail) {
    $trail->parent('licious.customer.profile.index');

    $trail->push(trans('shop::app.customer.account.wishlist.page-title'), route('licious.customer.wishlist.index'));
});

/**
 * Reviews.
 */
Breadcrumbs::for('licious.customer.reviews.index', function (BreadcrumbTrail $trail) {
    $trail->parent('licious.customer.profile.index');

    $trail->push(trans('shop::app.customer.account.review.index.page-title'), route('licious.customer.reviews.index'));
});

/**
 * Addresses.
 */
Breadcrumbs::for('licious.customer.addresses.index', function (BreadcrumbTrail $trail) {
    $trail->parent('licious.customer.profile.index');

    $trail->push(trans('shop::app.customer.account.address.index.page-title'), route('licious.customer.addresses.index'));
});

Breadcrumbs::for('licious.customer.addresses.create', function (BreadcrumbTrail $trail) {
    $trail->parent('licious.customer.addresses.index');

    $trail->push(trans('shop::app.customer.account.address.create.page-title'), route('licious.customer.addresses.create'));
});

Breadcrumbs::for('licious.customer.addresses.edit', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('licious.customer.addresses.index');

    $trail->push(trans('shop::app.customer.account.address.edit.page-title'), route('licious.customer.addresses.edit', $id));
});
