<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PropertyImage extends Model
{
    protected $table = 'property_images';

    protected $fillable = [
        'property_id',
        'property_image_key',
        'property_image_value',
    ];

    public function getUrlAttribute(): string
    {
        return Storage::url($this->property_image_value);
    }
}
