<?php

namespace Gaiproject\Paystack\Payment;

use Webkul\Payment\Payment\Payment;

class PaystackPopup extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'paystack_popup';

    /**
     * Checks if payment method is available
     *
     * @return array
     */
    public function isAvailable()
    {
        return $this->getConfigData('active') && paystack()->isReady();
    }

    public function getRedirectUrl()
    {
        return route('paystack.popup');
    }

    /**
     * Get secret key from Paystack config file
     */
    public function getPublicKey()
    {
        return $this->getConfigData('sandbox')
            ? config('services.paystack.test_public_key')
            : config('services.paystack.live_public_key');
    }

    public function getFormFields()
    {
        $cart = $this->getCart();
        return [
            'key' => $this->getPublicKey(),
            'amount' => $cart->grand_total * 100,
            'email' => $cart->billing_address->email,
            "currency" => "NGN", //(request()->currency != ""  ? request()->currency : "NGN"),
            "ref" => paystack()->genTranxRef()
        ];
    }
}
