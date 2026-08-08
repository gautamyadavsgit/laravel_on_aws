<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyFinancialDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'management_fee',
        'cash_reserve',
        'hold_period',
        'annual_appreciation',
        'aum_fee_1',
        'aum_fee_2',
        'aum_fee_3',
        'average_time_to_rent',
    ];

    public function propertyModel()
    {
        return $this->belongsTo(PropertyModel::class, 'property_id');
    }
}
