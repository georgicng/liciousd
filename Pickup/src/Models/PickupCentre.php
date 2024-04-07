<?php

namespace Gaiproject\Pickup\Models;

use Illuminate\Database\Eloquent\Model;
use Gaiproject\Pickup\Contracts\PickupCentre as PickupCentreContract;

class PickupCentre extends Model implements PickupCentreContract
{
    protected $fillable = [
        'name',
        'city',
        'phone',
        'address',
        'landmark',
        'rate',
        'location',
        'whatsapp',
        'email',
        'status',
        'country_id',
        'country_code',
        'state_id',
        'state_code',
        'additional'
    ];

    protected $casts = [
        'additional' => 'array',
    ];
}
