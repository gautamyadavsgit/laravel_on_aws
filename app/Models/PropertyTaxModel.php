<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyTaxModel extends Model
{
    use HasFactory;

    protected $table = 'properties_tax';

    protected $fillable = ['tax_key', 'tax_value'];

    public function propertyModel()
    {
        return $this->belongsTo(PropertyModel::class, 'property_id');
    }
}


