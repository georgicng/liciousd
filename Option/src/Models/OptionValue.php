<?php

namespace Gaiproject\Option\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Gaiproject\Option\Contracts\OptionValue as OptionValueContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OptionValue extends TranslatableModel implements OptionValueContract
{
    use HasFactory;
    public $translatedAttributes = ['label'];

    public $timestamps = false;

    protected $fillable = [
        'admin_name',
        'sort_order',
        'option_id',
    ];

    /**
     * Get the attribute that owns the attribute option.
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(OptionProxy::modelClass());
    }
}
