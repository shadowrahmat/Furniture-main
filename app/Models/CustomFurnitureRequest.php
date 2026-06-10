<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomFurnitureRequest extends Model
{
    protected $fillable = [
        'request_number', 'user_id', 'furniture_type_id', 'wood_type_id',
        'name', 'email', 'phone',
        'length', 'width', 'height', 'dimension_unit',
        'finish', 'color_preference',
        'budget_min', 'budget_max', 'delivery_preference',
        'additional_notes', 'status', 'admin_notes', 'is_read',
    ];

    protected function casts(): array
    {
        return [
            'length' => 'decimal:2',
            'width' => 'decimal:2',
            'height' => 'decimal:2',
            'budget_min' => 'decimal:2',
            'budget_max' => 'decimal:2',
            'is_read' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function furnitureType()
    {
        return $this->belongsTo(FurnitureType::class);
    }

    public function woodType()
    {
        return $this->belongsTo(WoodType::class);
    }

    public function files()
    {
        return $this->hasMany(CustomRequestFile::class);
    }

    public static function generateRequestNumber(): string
    {
        return 'CR-' . strtoupper(uniqid());
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending Review',
            'reviewing' => 'Under Review',
            'quoted' => 'Quotation Sent',
            'quotation_accepted' => 'Quotation Accepted',
            'quotation_rejected' => 'Quotation Rejected',
            'in_production' => 'In Production',
            'ready' => 'Ready',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'rejected' => 'Rejected',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'reviewing' => 'blue',
            'quoted' => 'indigo',
            'quotation_accepted' => 'green',
            'quotation_rejected' => 'red',
            'in_production' => 'orange',
            'ready' => 'teal',
            'completed' => 'green',
            'cancelled' => 'red',
            'rejected' => 'red',
            default => 'gray',
        };
    }
}
