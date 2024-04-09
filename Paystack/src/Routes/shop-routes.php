<?php
use Illuminate\Support\Facades\Route;
use Gaiproject\Paystack\Http\Controllers\Shop\PaymentController;

Route::group(['middleware' => ['web']], function () {
    Route::controller(PaymentController::class)->prefix('paystack')->group(function () {
        //Route::get('/redirect', 'redirect')->name('paystack.redirect');
        //Route::post('/pay', 'pay')->name('paystack.pay');
        Route::get('/callback', 'callback')->name('paystack.callback');
    });
});
