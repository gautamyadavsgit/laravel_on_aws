<?php

namespace App\Models;

use App\Enums\PropertyAvailability;
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

    protected function casts(): array
    {
        return [
            'availability' => PropertyAvailability::class,
        ];
    }

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

        while (static::withTrashed()->where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    // ==========================================
    // Presentation accessors
    // ==========================================

    public function getGalleryUrlsAttribute(): array
    {
        return $this->images->pluck('url')->all();
    }

    public function getHeroImageUrlAttribute(): ?string
    {
        return $this->images->first()?->url;
    }

    public function getLocationLabelAttribute(): string
    {
        $address = $this->address;

        return implode(', ', array_filter([$address?->city, $address?->state]));
    }

    // ==========================================
    // Standard Eloquent Relationship Definitions
    // ==========================================

    /** @return BelongsTo<Admin, $this> */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /** @return HasOne<PropertyAddress, $this> */
    public function address(): HasOne
    {
        return $this->hasOne(PropertyAddress::class, 'property_id');
    }

    /** @return HasMany<PropertyImageModel, $this> */
    public function images(): HasMany
    {
        return $this->hasMany(PropertyImageModel::class, 'property_id');
    }

    /** @return HasOne<PropertyDetails, $this> */
    public function details(): HasOne
    {
        return $this->hasOne(PropertyDetails::class, 'property_id');
    }

    /** @return HasOne<PropertyOffering, $this> */
    public function offering(): HasOne
    {
        return $this->hasOne(PropertyOffering::class, 'property_id');
    }

    /** @return HasMany<PropertyDocumentModel, $this> */
    public function documents(): HasMany
    {
        return $this->hasMany(PropertyDocumentModel::class, 'property_id');
    }
}
