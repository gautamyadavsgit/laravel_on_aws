<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyFloorplan extends Model
{
    use HasFactory;
    protected $table = 'property_floorplan';
    protected $fillable = ['key', 'value'];

    public function propertyModel()
    {
        return $this->belongsTo(PropertyModel::class, 'property_id');
    }
}
