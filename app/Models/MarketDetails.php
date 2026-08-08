<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketDetails extends Model
{
    use HasFactory;

    protected $table = 'market_details';
    protected $fillable = ['market_title', 'market_image', 'market_description'];
    public function propertyModel()
    {
        return $this->belongsTo(PropertyModel::class, 'property_id');
    }
}
