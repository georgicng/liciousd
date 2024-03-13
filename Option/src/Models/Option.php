<?php

namespace Gaiproject\Option\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Gaiproject\Option\Contracts\Option as OptionContract;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Option extends TranslatableModel implements OptionContract
{
    use HasFactory;

    public $translatedAttributes = ['name'];

    protected $fillable = [
        'code',
        'admin_name',
        'type',
    ];

    /**
     * Get the options.
     */
    public function values(): HasMany
    {
        return $this->hasMany(OptionValueProxy::modelClass());
    }

}
