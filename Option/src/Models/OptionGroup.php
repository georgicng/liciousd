<?php

namespace Gaiproject\Option\Models;

use Illuminate\Database\Eloquent\Model;
use Gaiproject\Option\Contracts\OptionGroup as OptionGroupContract;
use Webkul\Attribute\Models\AttributeFamilyProxy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OptionGroup extends Model implements OptionGroupContract
{
    public $timestamps = false;

    protected $fillable = [
        'attribute_family_id',
        'name',
        'column',
        'position',
        'is_user_defined',
    ];

    /**
     * Get the options that owns the option group.
     */
    public function custom_options()
    {
        return $this->belongsToMany(OptionProxy::modelClass(), 'option_group_mappings')
            ->withPivot('position')
            ->orderBy('pivot_position', 'asc');
    }

    public function attributeFamily(): BelongsTo
    {
        return $this->belongsTo(AttributeFamilyProxy::modelClass());
    }
}
