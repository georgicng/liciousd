<?php

use Illuminate\Support\Facades\Route;
use Gaiproject\CityShipping\Http\Controllers\Admin\CSController;

Route::group(['middleware' => ['admin'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('settings')->group(function () {
        Route::controller(CSController::class)->prefix('cs')->group(function () {
            Route::get('', 'index')->name('cs.settings.index');

            Route::post('create', 'store')->name('admin.settings.cs.store');

            Route::get('edit/{id}', 'edit')->name('admin.settings.cs.edit');

            Route::put('edit', 'update')->name('admin.settings.cs.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.settings.cs.delete');;
        });
    });
});
