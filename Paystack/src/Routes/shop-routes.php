<?php
use Illuminate\Support\Facades\Route;
use Gaiproject\Paystack\Http\Controllers\Shop\PaymentController;

Route::group(['middleware' => ['web']], function () {
    Route::controller(PaymentController::class)->prefix('paystack')->group(function () {
        Route::get('/redirect', 'redirect')->name('paystack.redirect');
        Route::get('/success', 'success')->name('paystack.success');
        Route::get('/cancel', 'cancel')->name('paystack.cancel');
        Route::get('/pay', 'popup')->name('paystack.popup');
    });
});
