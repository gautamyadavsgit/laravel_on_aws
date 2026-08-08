<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyAacf extends Model
{
    use HasFactory;


    protected $table = 'property_aacf';
    protected $fillable = [
        'annual_rent_amount',
        'annual_rent_gross_yield',
        'aacf_expences',
        'aacf_net',
    ];
    public function propertyModel()
    {
        return $this->belongsTo(PropertyModel::class, 'property_id');
    }
}
