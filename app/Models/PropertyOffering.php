<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
