<?php

namespace Gaiproject\CityShipping\Carriers;

use Gaiproject\CityShipping\Repositories\ShippingCityRepository;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Shipping\Carriers\AbstractShipping;

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

       return $this->getRate();
   }

    /**
     * Get rate.
     *
     * @return \Webkul\Checkout\Models\CartShippingRate
     */
    public function getRate(): \Webkul\Checkout\Models\CartShippingRate
    {
        $cart = Cart::getCart();
        $records = app(ShippingCityRepository::class)->findWhere([
            'state_code' => $cart->ShippingAddress->state,
            'country_code' => $cart->ShippingAddress->country,
            'name' => $cart->ShippingAddress->city
        ]);

        if ($records->isEmpty()) {
            return false;
        }

        $rate = $records->first()->rate ?? $this->getConfigData('default_rate');

        $cartShippingRate = new CartShippingRate;

        $cartShippingRate->carrier = $this->getCode();
        $cartShippingRate->carrier_title = $this->getConfigData('title');
        $cartShippingRate->method = $this->getMethod();
        $cartShippingRate->method_title = $this->getConfigData('title');
        $cartShippingRate->method_description = $this->getConfigData('description');
        $cartShippingRate->price = 0;
        $cartShippingRate->base_price = 0;

        if ($this->getConfigData('type') == 'per_unit') {
            foreach ($cart->items as $item) {
                if ($item->getTypeInstance()->isStockable()) {
                    $cartShippingRate->price += core()->convertPrice($rate) * $item->quantity;
                    $cartShippingRate->base_price += $rate * $item->quantity;
                }
            }
        } else {
            $cartShippingRate->price = core()->convertPrice($rate);
            $cartShippingRate->base_price = $rate;
        }

        return $cartShippingRate;
    }
}
