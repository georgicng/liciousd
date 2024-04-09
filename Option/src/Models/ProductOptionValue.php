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
                $_value = $value;
                $_value['base_increment'] = floatval("{$_value['prefix']}{$_value['price']}");
                $_value['increment'] = core()->convertPrice($_value['base_increment']);
                return $_value;
            },
        );
    }

    /* public function getRealValueAttribute() {
        if(is_array($this->value) && array_is_list($this->value)) {
            return array_map(function($value) {
                $value['increment'] = core()->convertPrice(floatval("{$value['prefix']}{$value['price']}"));
                return $value;
            }, $this->value);
        }
        $value = $this->value;
        $value['increment'] = core()->convertPrice(floatval("{$value['prefix']}{$value['price']}"));
        return $value;
    } */
}
