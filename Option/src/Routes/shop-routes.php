<?php

use Illuminate\Support\Facades\Route;
use Gaiproject\Option\Http\Controllers\Shop\OptionController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency'], 'prefix' => 'option'], function () {
    Route::get('', [OptionController::class, 'index'])->name('shop.option.index');
});