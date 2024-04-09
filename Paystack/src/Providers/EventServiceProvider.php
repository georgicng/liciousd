<?php

namespace Gaiproject\Paystack\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Webkul\Theme\ViewRenderEventManager;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('bagisto.shop.layout.body.after', static function(ViewRenderEventManager $viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('paytsack::checkout.onepage.paystack');
        });

        //Event::listen('sales.invoice.save.after', 'Webkul\Paypal\Listeners\Transaction@saveTransaction');
    }
}
