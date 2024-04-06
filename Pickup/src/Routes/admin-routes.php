<?php

use Illuminate\Support\Facades\Route;
use Gaiproject\Pickup\Http\Controllers\Admin\PickupController;

Route::group(['middleware' => ['admin'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('settings')->group(function () {
        Route::controller(PickupController::class)->prefix('pickup')->group(function () {
            Route::get('', 'index')->name('admin.settings.pickup.index');

            Route::post('create', 'store')->name('admin.settings.pickup.store');

            Route::get('edit/{id}', 'edit')->name('admin.settings.pickup.edit');

            Route::put('edit', 'update')->name('admin.settings.pickup.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.settings.pickup.delete');;
        });
    });
});
