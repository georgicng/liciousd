<?php

namespace Gaiproject\Paystack\Payment;

use Webkul\Payment\Payment\Payment;

class PaystackRedirect extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'paystack_redirect';


    /**
     * Checks if payment method is available
     *
     * @return array
     */
    public function isAvailable()
    {
        return $this->getConfigData('active')
            && paystack()->isReady()
            && !core()->getConfigData('sales.payment_methods.paystack_popup.active');
    }

    public function getRedirectUrl()
    {
        return route('paystack.redirect');
    }
}
