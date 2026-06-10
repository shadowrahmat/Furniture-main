<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomFurnitureRequest extends Model
{
    protected $fillable = [
        'request_number', 'user_id', 'furniture_type_id', 'wood_type_id',
        'name', 'email', 'phone', 'customer_address',
        'length', 'width', 'height', 'dimension_unit', 'quantity',
        'finish', 'color_preference', 'design_style', 'upholstery_fabric',
        'budget_min', 'budget_max', 'delivery_preference', 'delivery_date', 'delivery_address',
        'additional_notes', 'status', 'admin_notes', 'is_read',
    ];

    protected function casts(): array
    {
        return [
            'length' => 'decimal:2',
            'width' => 'decimal:2',
            'height' => 'decimal:2',
            'quantity' => 'integer',
            'budget_min' => 'decimal:2',
            'budget_max' => 'decimal:2',
            'delivery_date' => 'date',
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

    /**
     * Generate the next sequential request number for the current year.
     * Must be called within a DB transaction to safely lock the table
     * against concurrent inserts.
     */
    public static function generateRequestNumber(): string
    {
        $prefix = 'CFR-' . now()->year . '-';

        $last = static::where('request_number', 'like', $prefix . '%')
            ->lockForUpdate()
            ->orderByDesc('id')
            ->first();

        $next = $last ? ((int) substr($last->request_number, strlen($prefix))) + 1 : 1;

        return $prefix . str_pad((string) $next, 6, '0', STR_PAD_LEFT);
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
