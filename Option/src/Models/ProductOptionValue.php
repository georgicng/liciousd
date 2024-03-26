<?php

namespace Gaiproject\Option\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Product\Models\ProductProxy;
use Gaiproject\Option\Contracts\ProductOptionValue as ProductOptionValueContract;

class ProductOptionValue extends Model implements ProductOptionValueContract
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'option_id',
        'required',
        'value',
    ];

    /**
     * Get the option that owns the option value.
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(OptionProxy::modelClass());
    }

    /**
     * Get the product that owns the attribute value.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }
}
