<?php

namespace App\Models;

use App\Enums\PropertyAvailability;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Property extends Model
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
        'address_1',
        'address_2',
        'city',
        'state',
        'zip',
        'type',
        'bedrooms',
        'baths',
        'half_baths',
        'sleeps',
        'garages',
        'square_feets',
        'stories',
        'units',
        'lot_size',
        'year_built',
        'zoning',
        'value',
        'offering_purchase',
        'offering_build_cost',
        'offering_improvements',
        'offering_closing_cost',
        'offering_sourcing_fees',
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
        static::creating(function (Property $property) {
            if (empty($property->slug) && ! empty($property->name)) {
                $property->slug = static::generateUniqueSlug($property->name);
            }
        });

        static::updating(function (Property $property) {
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
        return implode(', ', array_filter([$this->city, $this->state]));
    }

    // ==========================================
    // Query scopes (public listing)
    // ==========================================

    /**
     * Apply the public listing filters (q, location, property_type, min_price, max_price, bedrooms, bathrooms).
     * Values are expected to be vetted already (see ListPropertiesRequest).
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query
            ->when($filters['q'] ?? null, function (Builder $query, string $keyword) {
                $like = static::containsPattern($keyword);
                $query->where(fn (Builder $q) => $q->where('name', 'LIKE', $like)
                    ->orWhere('description', 'LIKE', $like)
                    ->orWhere('management_company', 'LIKE', $like)
                    ->orWhere('address_1', 'LIKE', $like)
                    ->orWhere('city', 'LIKE', $like)
                    ->orWhere('state', 'LIKE', $like)
                    ->orWhere('zip', 'LIKE', $like));
            })
            ->when($filters['location'] ?? null, function (Builder $query, string $location) {
                $like = static::containsPattern($location);
                $query->where(fn (Builder $q) => $q->where('city', 'LIKE', $like)
                    ->orWhere('state', 'LIKE', $like)
                    ->orWhere('zip', 'LIKE', $like));
            })
            ->when($filters['property_type'] ?? null, fn (Builder $query, string $type) => $query->where('type', $type))
            ->when($filters['min_price'] ?? null, fn (Builder $query, $min) => $query->where('value', '>=', (int) $min))
            ->when($filters['max_price'] ?? null, fn (Builder $query, $max) => $query->where('value', '<=', (int) $max))
            ->when($filters['bedrooms'] ?? null, fn (Builder $query, $beds) => $query->where('bedrooms', '>=', (int) $beds))
            ->when($filters['bathrooms'] ?? null, fn (Builder $query, $baths) => $query->where('baths', '>=', (int) $baths));
    }

    /**
     * Sort by latest (default), oldest, price_asc or price_desc; id breaks ties so pages stay stable.
     */
    public function scopeSortedBy(Builder $query, ?string $sort): void
    {
        match ($sort) {
            'price_asc' => $query->orderBy('value')->orderBy('id'),
            'price_desc' => $query->orderByDesc('value')->orderByDesc('id'),
            'oldest' => $query->oldest()->orderBy('id'),
            default => $query->latest()->latest('id'),
        };
    }

    /**
     * Distinct cities, states and types for the listing filter dropdowns.
     *
     * @return array{cities: list<string>, states: list<string>, types: list<string>}
     */
    public static function filterOptions(): array
    {
        $distinct = fn (string $column) => static::whereNotNull($column)
            ->where($column, '!=', '')
            ->distinct()
            ->orderBy($column)
            ->pluck($column)
            ->all();

        return [
            'cities' => $distinct('city'),
            'states' => $distinct('state'),
            'types' => $distinct('type'),
        ];
    }

    /**
     * Contains-pattern with the user's own % and _ treated literally.
     */
    private static function containsPattern(string $term): string
    {
        return '%'.addcslashes($term, '%_\\').'%';
    }

    // ==========================================
    // Standard Eloquent Relationship Definitions
    // ==========================================

    /** @return HasMany<PropertyImage, $this> */
    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class, 'property_id');
    }
}
