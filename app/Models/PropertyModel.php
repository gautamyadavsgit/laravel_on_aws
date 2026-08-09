<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'properties';
    protected $fillable = [
        'name',
        'description',
        'management_company',
        'admin_id',
        'availability',
    ];

    public function propertyAddress(): HasOne
    {
        return $this->hasOne('\App\Models\PropertyAddress', 'property_id');
    }
    public function propertyImage(): HasMany
    {
        return $this->hasMany('\App\Models\PropertyImageModel', 'property_id');
    }
    public function propertyAmenities(): HasMany
    {
        return $this->hasMany('\App\Models\PropertyAmenity', 'property_id');
    }
    public function propertyDetails(): HasOne
    {
        return $this->hasOne('\App\Models\PropertyDetails', 'property_id');
    }
    public function propertyFloorplan(): HasMany
    {
        return $this->hasMany('\App\Models\PropertyFloorplan', 'property_id');
    }

    public function propertyOffering(): HasOne
    {
        return $this->hasOne('\App\Models\PropertyOffering', 'property_id');
    }

    public function propertyDocumentModel(): HasMany
    {
        return $this->hasMany('\App\Models\PropertyDocumentModel', 'property_id');
    }

    public function propertyMetrics(): HasOne
    {
        return $this->hasOne(\App\Models\PropertyMetrics::class, 'property_id');
    }
}
