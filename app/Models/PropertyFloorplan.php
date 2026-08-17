<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyFloorplan extends Model
{
    use HasFactory;

    protected $table = 'property_floorplan';

    protected $fillable = [
        'property_id',
        'key',
        'value',
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
