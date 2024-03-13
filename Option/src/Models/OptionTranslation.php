<?php

namespace Gaiproject\Option\Models;

use Illuminate\Database\Eloquent\Model;
use Gaiproject\Option\Contracts\OptionTranslation as OptionTranslationContract;

class OptionTranslation extends Model implements OptionTranslationContract
{
    public $timestamps = false;

    protected $fillable = ['name'];
}
