<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyDetail extends Model
{
    use HasFactory;

    protected $table = 'property_details';

    protected $fillable = [
        'property_id',
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
        'zoning',
        'value',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
