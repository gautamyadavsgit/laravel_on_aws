<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyAmenity extends Model
{
    use HasFactory;

    protected $table = 'property_amenities';

    protected $fillable = [
        'property_id',
        'property_amenities',
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
