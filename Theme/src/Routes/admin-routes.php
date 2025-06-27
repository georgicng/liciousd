<?php

use Illuminate\Support\Facades\Route;
use Gaiproject\Theme\Http\Controllers\Admin\MenuController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url')], function () {
    Route::controller(MenuController::class)->prefix('settings/menu')->group(function () {
        Route::get('', 'index')->name('admin.settings.menu.index');
        Route::post('', 'store')->name('admin.settings.menu.store');
    });
});
