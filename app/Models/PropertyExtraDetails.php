<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyExtraDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'deed_fraction_1',
        'deed_fraction_2',
        'leveraged',
        'leverage_amount',
        'leverage_percent',
        'rent_rate',
        'market_rent_rate',
        'occupancy_rate',
        'occupancy_status',
    ];
    public function propertyModel()
    {
        return $this->belongsTo(PropertyModel::class, 'property_id');
    }
}
