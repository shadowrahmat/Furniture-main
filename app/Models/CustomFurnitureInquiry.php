<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomFurnitureInquiry extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'furniture_type', 'space_type', 'dimensions',
        'material_preference', 'color_preference', 'budget_min', 'budget_max',
        'description', 'reference_image', 'status', 'admin_notes', 'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'budget_min' => 'decimal:2',
            'budget_max' => 'decimal:2',
        ];
    }

    public function getBudgetAttribute(): ?string
    {
        if ($this->budget_min && $this->budget_max) {
            return $this->budget_min . '–' . $this->budget_max;
        }
        return $this->budget_min ?? $this->budget_max ?? null;
    }
}
