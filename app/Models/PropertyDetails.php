<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'bedrooms',
        'baths',
        'half_baths',
        'sleeps',
        'garages',
        'square_feets',
        'stories',
        'units',
        'lot_size',
        'year_built',
    ];
    public function propertyModel()
    {
        return $this->belongsTo(PropertyModel::class, 'property_id');
    }
}
