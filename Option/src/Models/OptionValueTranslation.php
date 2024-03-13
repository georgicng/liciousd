<?php

namespace Gaiproject\Option\Models;

use Illuminate\Database\Eloquent\Model;
use Gaiproject\Option\Contracts\OptionValueTranslation as OptionValueTranslationContract;

class OptionValueTranslation extends Model implements OptionValueTranslationContract
{
    public $timestamps = false;

    protected $fillable = ['label'];
}
