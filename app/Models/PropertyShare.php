<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyShare extends Model
{
    use HasFactory;
    protected $fillable = [
        'equity_raise',
        'price_per_share_deed',
        'total_developer_share_deeds',
        'total_raise_share_deeds',
        'total_share_deeds',
        'first_dividend_date',
        'seccond_dividend_date',
    ];
    public function propertyModel()
    {
        return $this->belongsTo(PropertyModel::class, 'property_id');
    }
}
