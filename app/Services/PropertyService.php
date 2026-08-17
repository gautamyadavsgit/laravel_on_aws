<?php

namespace App\Services;

use App\Models\PropertyModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class PropertyService
{
    /**
     * Cache TTL in seconds (10 minutes)
     */
    protected const CACHE_TTL_SECONDS = 600;

    /**
     * Get paginated properties for the invest page with Cache facade
     */
    public function getPaginatedProperties(int $perPage = 9): LengthAwarePaginator
    {
        $currentPage = request()->get('page', 1);
        $cacheKey = "investor_properties_page_{$currentPage}_limit_{$perPage}";

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($perPage) {
            $paginator = PropertyModel::with(['propertyImage', 'propertyAddress', 'propertyMetrics', 'propertyDetails'])
                ->latest()
                ->paginate($perPage)
                ->onEachSide(2)
                ->withQueryString();

            // Ensure all loaded properties have a slug
            foreach ($paginator->items() as $prop) {
                if (empty($prop->slug) && ! empty($prop->name)) {
                    $prop->slug = PropertyModel::generateUniqueSlug($prop->name, $prop->id);
                    $prop->saveQuietly();
                }
            }

            return $paginator;
        });
    }

    /**
     * Get property by its slug with caching and eager-loaded relations
     */
    public function getPropertyBySlug(string $slug): ?PropertyModel
    {
        $cacheKey = "property_slug_{$slug}";

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($slug) {
            return PropertyModel::with([
                'propertyImage',
                'propertyFloorplan',
                'propertyDocumentModel',
                'propertyAddress',
                'propertyDetails',
                'propertyAmenities',
                'propertyMetrics',
            ])
                ->where('slug', $slug)
                ->first();
        });
    }

    /**
     * Get property by ID or return first featured property
     */
    public function getPropertyById(?string $id = null): ?PropertyModel
    {
        $query = PropertyModel::with([
            'propertyImage',
            'propertyFloorplan',
            'propertyDocumentModel',
            'propertyAddress',
            'propertyDetails',
            'propertyAmenities',
            'propertyMetrics',
        ]);

        $property = $id ? $query->find($id) : $query->first();

        if ($property && empty($property->slug) && ! empty($property->name)) {
            $property->slug = PropertyModel::generateUniqueSlug($property->name, $property->id);
            $property->saveQuietly();
        }

        return $property;
    }

    /**
     * Invalidate cached property pages and individual property caches
     */
    public function clearPropertiesCache(?string $slug = null): void
    {
        if ($slug) {
            Cache::forget("property_slug_{$slug}");
        }

        // Flush commonly indexed page caches (pages 1 to 20)
        for ($page = 1; $page <= 20; $page++) {
            Cache::forget("investor_properties_page_{$page}_limit_9");
        }
    }
}
