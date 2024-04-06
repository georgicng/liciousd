<?php

namespace Gaiproject\Pickup\Carriers;

use Config;

use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Inventory\Models\InventorySource;

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
        if (! $this->isAvailable()) {
            return false;
        }

        $inventories = $this->getInventories();

        if ($inventories) {
            foreach ($inventories as $code => $inventorySource) {
                $pickup = new CartShippingRate;

                $pickup->carrier = 'pickup';
                $pickup->carrier_title = __('pickup::app.admin.system.pickup');
                $pickup->method = 'pickup_' . $code;
                $pickup->method_title = $this->getConfigData('title') .' '. $inventorySource['title'];
                $pickup->method_description = $inventorySource['description'];
                $pickup->price = 0;
                $pickup->base_price = 0;

                $pickupMethods[] = $pickup;
            }
        }

        return $pickupMethods;

    }

    /**
     * Get all inventories
     *
     * @return inventoryData|false
     */
    public function getInventories()
    {

        $inventories = InventorySource::where('status', 1)->get();

        if (isset ($inventories)) {
            foreach ($inventories as $inventory) {
                if ($this->getConfigData('display_address')) {
                    $description = $inventory->street . ' ' . $inventory->postcode . ' ' . $inventory->city . ' ' . $inventory->country;
                } else {
                    $description = $this->getConfigData('description');
                }

                $inventoryData[$inventory->id] = [
                    'title'       => $inventory->name,
                    'description' => $description
                ];
            }

            return $inventoryData;
        } else {
            return false;
        }
    }
}
