<?php

namespace Gaiproject\Paystack\Payment;

use Webkul\Payment\Payment\Payment;
use Illuminate\Support\Facades\Storage;

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

     /**
     * Get payment method image.
     *
     * @return array
     */
    public function getImage()
    {
        $url = $this->getConfigData('image');

        return $url ? Storage::url($url) : bagisto_asset('images/cash-on-delivery.png', 'shop');
    }
}
