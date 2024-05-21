<?php

namespace Gaiproject\Option\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'position',
        'min',
        'max',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    /**
     * Get the option that owns the option value.
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(OptionProxy::modelClass())->with(['values']);
    }

    /**
     * Get the product that owns the attribute value.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }

    protected function value(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!is_array($value)) {
                    $value = json_decode($value, true);
                }
                if (array_is_list($value)) {
                    return array_map(function ($_value) {
                        $_value['base_increment'] = floatval("{$_value['prefix']}{$_value['price']}");
                        $_value['increment'] = core()->convertPrice($_value['base_increment']);
                        return $_value;
                    }, $value);
                }
                if (array_key_exists('prefix', $value) && array_key_exists('price', $value)) {
                    $_value = $value;
                    $_value['base_increment'] = floatval("{$_value['prefix']}{$_value['price']}");
                    $_value['increment'] = core()->convertPrice($_value['base_increment']);
                    return $_value;
                }
                return $value;
            },
        );
    }

}
