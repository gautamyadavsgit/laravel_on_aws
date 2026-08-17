<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PropertyModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'properties';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'management_company',
        'admin_id',
        'availability',
    ];

    /**
     * Model boot lifecycle events
     */
    protected static function booted(): void
    {
        static::creating(function (PropertyModel $property) {
            if (empty($property->slug) && ! empty($property->name)) {
                $property->slug = static::generateUniqueSlug($property->name);
            }
        });

        static::updating(function (PropertyModel $property) {
            if (empty($property->slug) && ! empty($property->name)) {
                $property->slug = static::generateUniqueSlug($property->name, $property->id);
            }
        });
    }

    /**
     * Generate unique slug based on property name
     */
    public static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        if (empty($baseSlug)) {
            $baseSlug = 'property';
        }

        $slug = $baseSlug;
        $counter = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    // ==========================================
    // Standard Eloquent Relationship Definitions
    // ==========================================

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function address(): HasOne
    {
        return $this->hasOne(PropertyAddress::class, 'property_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImageModel::class, 'property_id');
    }

    public function amenities(): HasMany
    {
        return $this->hasMany(PropertyAmenity::class, 'property_id');
    }

    public function details(): HasOne
    {
        return $this->hasOne(PropertyDetails::class, 'property_id');
    }

    public function floorplans(): HasMany
    {
        return $this->hasMany(PropertyFloorplan::class, 'property_id');
    }

    public function offering(): HasOne
    {
        return $this->hasOne(PropertyOffering::class, 'property_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PropertyDocumentModel::class, 'property_id');
    }

    public function metrics(): HasOne
    {
        return $this->hasOne(PropertyMetrics::class, 'property_id');
    }

    // ==========================================
    // Backward Compatibility Relationship Aliases
    // ==========================================

    public function propertyAddress(): HasOne
    {
        return $this->address();
    }

    public function propertyImage(): HasMany
    {
        return $this->images();
    }

    public function propertyAmenities(): HasMany
    {
        return $this->amenities();
    }

    public function propertyDetails(): HasOne
    {
        return $this->details();
    }

    public function propertyFloorplan(): HasMany
    {
        return $this->floorplans();
    }

    public function propertyOffering(): HasOne
    {
        return $this->offering();
    }

    public function propertyDocumentModel(): HasMany
    {
        return $this->documents();
    }

    public function propertyMetrics(): HasOne
    {
        return $this->metrics();
    }
}
