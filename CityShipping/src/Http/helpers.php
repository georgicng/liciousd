<?php

use Gaiproject\CityShipping\Repositories\ShippingCityRepository;

if (!function_exists('getGroupedCities')) {
    function getGroupedCities()
    {
        $collection = [];

        foreach (app(ShippingCityRepository::class)->all() as $city) {
             if (!$city->status) {
                continue;
            }

            if (!isset($collection[$city->country_code])) {
                $collection[$city->country_code] = [];
            }

            $collection[$city->country_code][$city->state_code][] = $city;
        }

        return $collection;
    }
}
