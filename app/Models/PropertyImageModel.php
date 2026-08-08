<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PropertyModel;

class PropertyImageModel extends Model
{
    use HasFactory;

    protected $table = 'property_images';


    protected $fillable = [
        'property_image_key',
        'property_image_value',
        'property_id',
    ];

    public function propertyModel()
    {
        return $this->belongsTo(PropertyModel::class, 'property_id');
    }

}
