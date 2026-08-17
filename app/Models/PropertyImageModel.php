<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyImageModel extends Model
{
    use HasFactory;

    protected $table = 'property_images';

    protected $fillable = [
        'property_id',
        'property_image_key',
        'property_image_value',
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
