<?php

namespace Gaiproject\Pickup\Models;

use Illuminate\Database\Eloquent\Model;
use Gaiproject\Pickup\Contracts\PickupCentre as PickupCentreContract;

class PickupCentre extends Model implements PickupCentreContract
{
    protected $fillable = [];
}