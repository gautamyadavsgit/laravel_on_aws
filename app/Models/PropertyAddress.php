<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PropertyModel;

class PropertyAddress extends Model
{
    use HasFactory;
    protected $table = 'property_address';
    protected $fillable = [
        'address_1',
        'address_2',
        'city',
        'state',
        'zip',
    ];

    public function propertyModel()
    {
        return $this->belongsTo(PropertyModel::class, 'property_id');
    }
}
