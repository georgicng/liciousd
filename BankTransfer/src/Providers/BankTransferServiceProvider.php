<?php

namespace Gaiproject\BankTransfer\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class BankTransferServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'bank');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/paymentmethods.php', 'payment_methods'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );

        $this->loadViewsFrom(__DIR__ . '/../Resources/views/shop', 'shop');
        Event::listen('bagisto.shop.checkout.payment-method.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('shop::checkout.payment');
        });
    }
}
