<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FurnitureType extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'image',
        'starting_price', 'is_active', 'display_order',
    ];

    protected function casts(): array
    {
        return [
            'starting_price' => 'decimal:2',
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
