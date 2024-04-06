<?php

namespace Gaiproject\CityShipping\Models;

use Illuminate\Database\Eloquent\Model;
use Gaiproject\CityShipping\Contracts\ShippingCity as ShippingCityContract;

class ShippingCity extends Model implements ShippingCityContract
{
    protected $fillable = [];
}
