<?php

namespace Gaiproject\Paystack;

use GuzzleHttp\Client;
use Gaiproject\Paystack\Exceptions\IsNullException;

class Paystack
{
    /**
     * Transaction Verification Successful
     */
    const VS = 'Verification successful';

    /**
     *  Invalid Transaction reference
     */
    const ITF = "Invalid transaction reference";

    /**
     * Issue Secret Key from your Paystack Dashboard
     * @var string
     */
    protected $secretKey;

    /**
     * Instance of Client
     * @var Client
     */
    protected $client;

    /**
     *  Response from requests made to Paystack
     * @var mixed
     */
    protected $response;

    /**
     * Paystack API base Url
     * @var string
     */
    protected $baseUrl;

    /**
     * Redirect Url
     * @var string
     */
    protected $url;

    /**
     * Authorization Url - Paystack payment page
     * @var string
     */
    protected $authorizationUrl;

    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'paystack';

    public function __construct()
    {
        $this->setBaseUrl();
        $this->setKey();
        $this->initClient();
    }

    /**
     * Get secret key from Paystack config file
     */
    public function isReady()
    {
        return $this->baseUrl && $this->secretKey;
    }

    /**
     * Get Base Url from Paystack config file
     */
    public function setBaseUrl()
    {
        $this->baseUrl = config('services.paystack.payment_url');
    }

    /**
     * Get secret key from Paystack config file
     */
    public function setKey()
    {
        $this->secretKey = $this->getConfigData('sandbox')
            ? config('services.paystack.test_secret_key')
            : config('services.paystack.live_secret_key');
    }

    /**
     * Set options for making the Client request
     */
    public function initClient()
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

     * Initiate a payment request to Paystack
     * Included the option to pass the payload to this method for situations
     * when the payload is built on the fly (not passed to the controller from a view)
     * @return Paystack
     */

    public function makePaymentRequest($data)
    {
        $this->setHttpResponse('/transaction/initialize', 'POST', $data);

        return $this;
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

        $this->response = $this->client->{strtolower($method)}(
            $relativeUrl,
            ["body" => json_encode($body)]
        );

        return $this;
    }

    /**
     * Get the whole response from a get operation
     * @return array
     */
    private function getResponse()
    {
        return json_decode($this->response->getBody(), true);
    }

    /**
     * Get the data response from a get operation
     * @return array
     */
    private function getData()
    {
        return $this->getResponse()['data'];
    }

    /**
     * Get the authorization url from the callback response
     * @return Paystack
     */
    public function getAuthorizationUrl($data = null)
    {
        $this->makePaymentRequest($data);

        $this->url = $this->getData()['authorization_url'];

        return $this;
    }

    /**
     * True or false condition whether the transaction is verified
     * @return boolean
     */
    public function isValid($reference)
    {
        $this->setHttpResponse("/transaction/verify/{$reference}", "GET", []);

        $result = $this->getResponse()['message'];

        switch ($result) {
            case self::VS:
                $validate = true;
                break;
            case self::ITF:
                $validate = false;
                break;
            default:
                $validate = false;
                break;
        }

        return $validate;
    }

    /**
     * Fluent method to redirect to Paystack Payment Page
     */
    public function redirectNow()
    {
        return redirect($this->url);
    }

    /**
     * Get Access code from transaction callback respose
     * @return string
     */
    public function getAccessCode()
    {
        return $this->getData()['access_code'];
    }

    /**
     * Generate a Unique Transaction Reference
     * @return string
     */
    public function genTranxRef()
    {
        return TransRef::getHashedToken();
    }

    /**
     * Returns payment method code
     *
     * @return array
     */
    public function getCode()
    {
        if (empty($this->code)) {
            // throw exception
        }

        return $this->code;
    }

    /**
     * Retrieve information from payment configuration
     *
     * @param  string  $field
     * @param  int|string|null  $channelId
     * @return mixed
     */
    public function getConfigData($field)
    {
        return core()->getConfigData('sales.payment_methods.' . $this->getCode() . '.' . $field);
    }
}
