<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyOffering extends Model
{
    use HasFactory;

    protected $table = 'property_offerings';

    protected $fillable = [
        'property_id',
        'offering_purchase',
        'offering_build_cost',
        'offering_improvements',
        'offering_closing_cost',
        'offering_sourcing_fees',
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
