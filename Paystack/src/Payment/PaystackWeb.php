<?php

namespace Gaiproject\Paystack\Payment;

use Webkul\Payment\Payment\Payment;

class PaystackWeb extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'paystack';


    private function getSecret()
    {
        return $this->getConfigData('mode') == 'live'
            ? $this->getConfigData('live_private_key')
            : $this->getConfigData('test_private_key');
    }

    private function getEndpoint()
    {
        return $this->getConfigData('base_url');
    }

    /**
     * Checks if payment method is available
     *
     * @return array
     */
    public function isAvailable()
    {
        return $this->getConfigData('active') && $this->getSecret() && $this->getEndpoint();
    }

    public function getRedirectUrl()
    {
        $cart = $this->getCart();
        return paystack()->getAuthorizationUrl([
            'amount' => $cart->grand_total * 100,
            'email' => $cart->billing_address->email,
            //"currency" => (request()->currency != ""  ? request()->currency : "NGN"),
            'callback_url' => route('paystack.callback')
        ])
            ->getRedirectUrl();
    }
}
