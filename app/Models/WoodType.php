<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WoodType extends Model
{
    protected $fillable = [
        'name', 'slug', 'short_description', 'description', 'image',
        'durability', 'recommended_use', 'color_texture',
        'price_adjustment_type', 'price_adjustment_value',
        'availability', 'is_active', 'display_order',
    ];

    protected function casts(): array
    {
        return [
            'price_adjustment_value' => 'decimal:2',
            'is_active' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    public function customFurnitureRequests()
    {
        return $this->hasMany(CustomFurnitureRequest::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
