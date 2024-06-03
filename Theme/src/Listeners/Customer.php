<?php

namespace Gaiproject\Theme\Listeners;

use Illuminate\Support\Facades\Mail;
use Gaiproject\Theme\Mail\Customer\EmailVerificationNotification;
use Gaiproject\Theme\Mail\Customer\NoteNotification;
use Gaiproject\Theme\Mail\Customer\RegistrationNotification;
use Gaiproject\Theme\Mail\Customer\SubscriptionNotification;
use Gaiproject\Theme\Mail\Customer\UpdatePasswordNotification;

class Customer extends Base
{
    /**
     * After customer is created
     *
     * @param  \Webkul\Customer\Contracts\Customer  $customer
     * @return void
     */
    public function afterCreated($customer)
    {
        if (core()->getConfigData('customer.settings.email.verification')) {
            try {
                if (! core()->getConfigData('emails.general.notifications.emails.general.notifications.verification')) {
                    return;
                }

                Mail::queue(new EmailVerificationNotification($customer));
            } catch (\Exception $e) {
                \Log::info('EmailVerificationNotification Error');

                report($e);
            }

            return;
        }

        try {
            if (! core()->getConfigData('emails.general.notifications.emails.general.notifications.registration')) {
                return;
            }

            Mail::queue(new RegistrationNotification($customer));
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Send mail on updating password.
     *
     * @param  \Webkul\Customer\Models\Customer  $customer
     * @return void
     */
    public function afterPasswordUpdated($customer)
    {
        try {
            Mail::queue(new UpdatePasswordNotification($customer));
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Send mail on subscribe
     *
     * @param  \Webkul\Customer\Models\Customer  $customer
     * @return void
     */
    public function afterSubscribed($customer)
    {
        try {
            Mail::queue(new SubscriptionNotification($customer));
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Send mail on creating Note
     *
     * @param  \Webkul\Customer\Models\Customer  $customer
     * @return void
     */
    public function afterNoteCreated($note)
    {
        if (! $note->customer_notified) {
            return;
        }

        try {
            Mail::queue(new NoteNotification($note));
        } catch (\Exception $e) {
            session()->flash('warning', $e->getMessage());
        }
    }
}
