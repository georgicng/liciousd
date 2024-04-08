<?php

namespace Gaiproject\Pickup\Carriers;

use Gaiproject\Pickup\Repositories\PickupCentreRepository;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Shipping\Carriers\AbstractShipping;

/**
 * Class Pickup.
 *
 */
class Pickup extends AbstractShipping
{
    /**
     * Shipment method code
     *
     * @var string
     */
    protected $code  = 'pickup';

    /**
     * Returns rate for pickup
     *
     * @return CartShippingRate|false
     */
    public function calculate()
    {
        if (!$this->isAvailable()) {
            return false;
        }

        return $this->getRates();
    }

    /**
     * Get rate.
     *
     * @return \Webkul\Checkout\Models\CartShippingRate
     */
    public function getRates(): array
    {
        $pickupMethods = [];
        $cart = Cart::getCart();
        $records = app(PickupCentreRepository::class)->findWhere([
            'state_code' => $cart->ShippingAddress->state,
            'country_code' => $cart->ShippingAddress->country
        ]);

        if ($records->isEmpty()) {
            return false;
        }

        foreach ($records as $record) {

            $rate = $record->rate ?? $this->getConfigData('default_rate');
            $cartShippingRate = new CartShippingRate;

            $cartShippingRate->carrier = "{$this->getCode()}_{$record->id}";
            $cartShippingRate->carrier_title = "{$this->getConfigData('title')}_{$record->city}";
            $cartShippingRate->method = $this->getMethod();
            $cartShippingRate->method_title = "{$this->getConfigData('title')}_{$record->city}";
            $cartShippingRate->method_description = "{$record->address}, {$record->city}";
            $cartShippingRate->price = core()->convertPrice($rate);
            $cartShippingRate->base_price = $rate;
            $pickupMethods[] = $cartShippingRate;
        }

        return $pickupMethods;
    }
}
