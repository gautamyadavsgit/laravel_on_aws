<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyUrl extends Model
{
    use HasFactory;

    protected $fillable = [
        'google_map',
        'zillow',
        'airbnb',
        'vrbo',
        'alt_listing_1',
        'alt_listing_2',
        'alt_listing_3',
    ];
    public function propertyModel()
    {
        return $this->belongsTo(PropertyModel::class, 'property_id');
    }
}
