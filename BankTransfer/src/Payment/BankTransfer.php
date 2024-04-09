<?php

namespace Gaiproject\BankTransfer\Payment;

use Webkul\Payment\Payment\Payment;

class BankTransfer extends Payment
{
   /**
    * Payment method code
    *
    * @var string
    */
    protected $code  = 'bank_transfer';

   /**
    * Get redirect url.
    *
    * @var string
    */
    public function getRedirectUrl()
    {
    }

    /**
     * Checks if payment method is available
     *
     * @return array
     */
    public function isAvailable()
    {
        return $this->getConfigData('active') && $this->getConfigData('account');
    }

    /**
     * Returns payment method additional information
     *
     * @return array
     */
    public function getAdditionalDetails()
    {
        if (empty($this->getConfigData('account'))) {
            return [];
        }

        return [
            'title' => trans('admin::app.configuration.bank-account'),
            'value' => $this->getConfigData('account'),
        ];
    }
}
