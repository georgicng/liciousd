<?php

namespace Gaiproject\Paystack\Payment;

use GuzzleHttp\Client;
use Gaiproject\Paystack\Exceptions\IsNullException;
use Gaiproject\Paystack\Exceptions\PaymentVerificationFailedException;
use Illuminate\Support\Facades\Redirect;
use Webkul\Payment\Payment\Payment;

class Paystack extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'paystack';
    protected $secretKey;
    protected $baseUrl;
    protected $authorizationUrl;
    protected $client;

    public function __construct()
    {
        $this->setKey();
        $this->setBaseUrl();
        $this->setRequestOptions();
    }

    /**
     * Get secret key from .env file
     */
    public function setKey()
    {
        $this->secretKey = $this->getConfigData('mode') == 'live'
            ? $this->getConfigData('live_private_key')
            : $this->getConfigData('test_private_key');
    }

    public function setBaseUrl()
    {
        $this->baseUrl = $this->getConfigData('base_url');
    }

    /**
     * Set options for making the Client request
     */
    private function setRequestOptions()
    {
        $authBearer = 'Bearer ' . $this->secretKey;

        $this->client = new Client(
            [
                'base_uri' => $this->baseUrl,
                'headers' => [
                    'Authorization' => $authBearer,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json'
                ]
            ]
        );
    }

    /**
     * @param string $relativeUrl
     * @param string $method
     * @param array $body
     * @return Paystack
     * @throws IsNullException
     */
    private function setHttpResponse($relativeUrl, $method, $body = [])
    {
        if (is_null($method)) {
            throw new IsNullException("Empty method not allowed");
        }

        return $this->client->{strtolower($method)}(
            $this->baseUrl . $relativeUrl,
            ["body" => json_encode($body)]
        );
    }

    public function getRedirectUrl()
    {
        return route('paystack.redirect');
    }

    public function getFormFields()
    {
        $cart = $this->getCart();
        $billingAddress = $cart->billing_address;
        $total = $cart->sub_total + ($cart->selected_shipping_rate ? $cart->selected_shipping_rate->price : 0);
        $data = [
            'amount' => $total,
            'email' => $billingAddress->email,
            'reference' => $cart->id,
            '_token' => csrf_token(),
            'reference' => TransRef::getHashedToken(),
            'quantity' => 1,
            "currency" => (request()->currency != ""  ? request()->currency : "NGN")
        ];
        return $data;
    }

    public function makePaymentRequest($callbackUrl = route('paystack.callback'))
    {
        $cart = $this->getCart();
        $billingAddress = $cart->billing_address;
        $total = $cart->sub_total + ($cart->selected_shipping_rate ? $cart->selected_shipping_rate->price : 0);

        $email = $billingAddress->email;
        $amount = $total * 100;

        $data = [
            'amount' => $amount,
            'email' => $email,
            'callback_url' => $callbackUrl,
        ];

        return $this->setHttpResponse('/transaction/initialize', 'POST', $data);
    }

    public function verifyPayment($transactionRef)
    {
        return $this->setHttpResponse("/transaction/verify/{$transactionRef}", "GET", []);
    }
}
