<?php

use Illuminate\Support\Facades\Route;
use Gaiproject\CityShipping\Http\Controllers\Admin\CSController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url')], function () {

    Route::controller(CSController::class)->prefix('settings/cs')->group(function () {
        Route::get('', 'index')->name('admin.settings.cs.index');

        Route::post('create', 'store')->name('admin.settings.cs.store');

        Route::get('edit/{id}', 'edit')->name('admin.settings.cs.edit');

        Route::put('edit', 'update')->name('admin.settings.cs.update');

        Route::delete('edit/{id}', 'destroy')->name('admin.settings.cs.delete');;
    });
});
