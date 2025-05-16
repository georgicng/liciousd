<?php
if (! function_exists('getPickupLocations')) {
    /**
     * Core helper.
     *
     * @return \Webkul\Core\Core
     */
    function getPickupLocations()
    {
        $records = app(\Gaiproject\Pickup\Repositories\PickupCentreRepository::class)->all();
        if ($records->isEmpty()) {
            return [];
        }
        return $records->reduce(function ($carry, $item) {
            $carry[$item->name." Pickup Centre"] = [
                'name' => $item->name,
                'address' => $item->address,
                'landmark' => $item->landmark,
                'city' => $item->city,
                'phone' => $item->phone,
                'whatsapp' => $item->whatsapp,
                'email' => $item->email,
                'additional' => $item->additional,
            ];
            return $carry;
        }, []);
    }
}
