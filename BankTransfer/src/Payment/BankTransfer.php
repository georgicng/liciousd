<?php

namespace Gaiproject\BankTransfer\Payment;

use Webkul\Payment\Payment\Payment;
use Illuminate\Support\Facades\Storage;


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
     * Get payment method image.
     *
     * @return array
     */
    public function getImage()
    {
        $url = $this->getConfigData('image');

        return $url ? Storage::url($url) : bagisto_asset('images/cash-on-delivery.png', 'shop');
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
            'title' => trans('admin::app.admin.system.additional'),
            'value' => [ 'account' => $this->getConfigData('account'), 'note' => $this->getConfigData('additional') ],
        ];
    }
}
