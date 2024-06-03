<?php

namespace Gaiproject\Theme\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'customer.registration.after' => [
            'Gaiproject\Theme\Listeners\Customer@afterCreated',
        ],

        'customer.password.update.after' => [
            'Gaiproject\Theme\Listeners\Customer@afterPasswordUpdated',
        ],

        'customer.subscription.after' => [
            'Gaiproject\Theme\Listeners\Customer@afterSubscribed',
        ],

        'customer.note.create.after' => [
            'Gaiproject\Theme\Listeners\Customer@afterNoteCreated',
        ],

        'checkout.order.save.after' => [
            'Gaiproject\Theme\Listeners\Order@afterCreated',
        ],

        'sales.order.cancel.after' => [
            'Gaiproject\Theme\Listeners\Order@afterCanceled',
        ],

        'sales.order.comment.create.after' => [
            'Gaiproject\Theme\Listeners\Order@afterCommented',
        ],

        'sales.invoice.save.after' => [
            'Gaiproject\Theme\Listeners\Invoice@afterCreated',
        ],

        'sales.invoice.send_duplicate_email' => [
            'Gaiproject\Theme\Listeners\Invoice@afterCreated',
        ],

        'sales.shipment.save.after' => [
            'Gaiproject\Theme\Listeners\Shipment@afterCreated',
        ],

        'sales.refund.save.after' => [
            'Gaiproject\Theme\Listeners\Refund@afterCreated',
        ],
    ];
}
