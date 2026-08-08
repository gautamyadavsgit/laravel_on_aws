<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyOffering extends Model
{
    use HasFactory;
    protected $fillable = [
        'offering_purchase',
        'offering_build_cost',
        'offering_improvements',
        'offering_closing_cost',
        'offering_sourcing_fees',
    ];

    public function propertyModel()
    {
        return $this->belongsTo(PropertyModel::class, 'property_id');
    }
}
