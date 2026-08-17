<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyAddress extends Model
{
    use HasFactory;

    protected $table = 'property_address';

    protected $fillable = [
        'property_id',
        'address_1',
        'address_2',
        'city',
        'state',
        'zip',
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
