<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyDetails extends Model
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
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(PropertyModel::class, 'property_id');
    }

    /**
     * Backward compatibility alias.
     */
    public function propertyModel(): BelongsTo
    {
        return $this->property();
    }
}
