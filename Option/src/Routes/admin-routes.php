<?php

use Illuminate\Support\Facades\Route;
use Gaiproject\Option\Http\Controllers\Admin\OptionController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url')], function () {
    Route::controller(OptionController::class)->prefix('options')->group(function () {
        Route::get('', 'index')->name('admin.options.index');
        Route::get('{id}/values', 'getOptionValues')->name('admin.options.values');

        Route::get('create', 'create')->name('admin.options.create');

        Route::post('create', 'store')->name('admin.options.store');

        Route::get('edit/{id}', 'edit')->name('admin.options.edit');

        Route::put('edit/{id}', 'update')->name('admin.options.update');

        Route::delete('edit/{id}', 'destroy')->name('admin.options.delete');

        Route::post('mass-delete', 'massDestroy')->name('admin.options.mass_delete');
    });
});
