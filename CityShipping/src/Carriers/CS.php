<?php

namespace Gaiproject\CityShipping\Carriers;

use Config;
use Webkul\Shipping\Carriers\AbstractShipping;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Shipping\Facades\Shipping;

class CS extends AbstractShipping
{
     /**
      * Code.
      *
      * @var string
      */
     protected $code  = 'cs';

     /**
      * Calculate.
      *
      * @return mixed
      */
     public function calculate()
     {
       if (! $this->isAvailable()) {
           return false;
       }

       $object = new CartShippingRate;

       $object->carrier = 'city-shipping';
       $object->carrier_title = $this->getConfigData('title');
       $object->method = 'city_shipping';
       $object->method_title = $this->getConfigData('title');
       $object->method_description = $this->getConfigData('description');
       $object->price = 0;
       $object->base_price = 0;

       if ($this->getConfigData('type') == 'per_unit') {
           foreach ($cart->items as $item) {
               if (
                   $this->getConfigData('base_amount') &&
                   $this->getConfigData('base_amount') > ($item->product->price)
               ) {
                   continue;
               }
               if ($item->product->getTypeInstance()->isStockable()) {
                   $object->price += core()->convertPrice($this->getConfigData('default_rate')) * $item->quantity;
                   $object->base_price += $this->getConfigData('default_rate') * $item->quantity;
               }
           }
       } else {
           if (
               $this->getConfigData('base_amount') &&
               $this->getConfigData('base_amount') > ($cart->sub_total)
           ) {
               return false;
           }
           $object->price = core()->convertPrice($this->getConfigData('default_rate'));
           $object->base_price = $this->getConfigData('default_rate');
       }

       return $object;
   }
}
