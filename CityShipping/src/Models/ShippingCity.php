<?php

namespace Gaiproject\CityShipping\Models;

use Illuminate\Database\Eloquent\Model;
use Gaiproject\CityShipping\Contracts\ShippingCity as ShippingCityContract;

class ShippingCity extends Model implements ShippingCityContract
{
    protected $fillable = [
        'name',
        'rate',
        'status',
        'additional',
        'country_id',
        'country_code',
        'state_id',
        'state_code',
    ];

    protected $casts = [
        'additional' => 'array',
    ];
}
