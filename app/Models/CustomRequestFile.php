<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomRequestFile extends Model
{
    protected $fillable = [
        'custom_furniture_request_id', 'file_path', 'original_name',
        'file_type', 'file_size', 'uploaded_by',
    ];

    public function customFurnitureRequest()
    {
        return $this->belongsTo(CustomFurnitureRequest::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
