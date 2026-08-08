<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalcPreset extends Model
{
    use HasFactory;
    protected $fillable = ['key', 'value'];
    public function propertyModel()
    {
        return $this->belongsTo(PropertyModel::class, 'property_id');
    }
}
