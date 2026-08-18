<?php

namespace App\Services;

use App\Models\PropertyAddress;
use App\Models\PropertyDetails;
use App\Models\PropertyFavorite;
use App\Models\PropertyMetrics;
use App\Models\PropertyModel;
use App\Models\User;
use App\Models\UserSearchLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PropertyService
{
    /**
     * Cache TTL in seconds (10 minutes)
     */
    protected const CACHE_TTL_SECONDS = 600;

    /**
     * Get paginated properties with dynamic multi-criteria filtering
     */
    public function getFilteredProperties(array $filters = [], int $perPage = 9): LengthAwarePaginator
    {
        $query = PropertyModel::with([
            'propertyImage',
            'propertyAddress',
            'propertyMetrics',
            'propertyDetails',
        ]);

        // 1. Keyword search (Name, Description, Management Company, or Address)
        if (! empty($filters['q'])) {
            $keyword = trim($filters['q']);
            $query->where(function (Builder $q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE', "%{$keyword}%")
                    ->orWhere('management_company', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('address', function (Builder $addrQuery) use ($keyword) {
                        $addrQuery->where('city', 'LIKE', "%{$keyword}%")
                            ->orWhere('state', 'LIKE', "%{$keyword}%")
                            ->orWhere('address_1', 'LIKE', "%{$keyword}%")
                            ->orWhere('zip', 'LIKE', "%{$keyword}%");
                    });
            });
        }

        // 2. Location filter (City / State)
        if (! empty($filters['location'])) {
            $location = trim($filters['location']);
            $query->whereHas('address', function (Builder $addrQuery) use ($location) {
                $addrQuery->where('city', 'LIKE', "%{$location}%")
                    ->orWhere('state', 'LIKE', "%{$location}%")
                    ->orWhere('zip', 'LIKE', "%{$location}%");
            });
        }

        // 3. Price range filter (Property Details Value)
        if (isset($filters['min_price']) && is_numeric($filters['min_price']) && $filters['min_price'] > 0) {
            $minPrice = (int) $filters['min_price'];
            $query->whereHas('details', function (Builder $detailsQuery) use ($minPrice) {
                $detailsQuery->where('value', '>=', $minPrice);
            });
        }

        if (isset($filters['max_price']) && is_numeric($filters['max_price']) && $filters['max_price'] > 0) {
            $maxPrice = (int) $filters['max_price'];
            $query->whereHas('details', function (Builder $detailsQuery) use ($maxPrice) {
                $detailsQuery->where('value', '<=', $maxPrice);
            });
        }

        // 4. Property Type filter
        if (! empty($filters['property_type'])) {
            $type = trim($filters['property_type']);
            $query->whereHas('details', function (Builder $detailsQuery) use ($type) {
                $detailsQuery->where('type', $type);
            });
        }

        // 5. Minimum Bedrooms
        if (! empty($filters['bedrooms']) && is_numeric($filters['bedrooms'])) {
            $bedrooms = (int) $filters['bedrooms'];
            $query->whereHas('details', function (Builder $detailsQuery) use ($bedrooms) {
                $detailsQuery->where('bedrooms', '>=', $bedrooms);
            });
        }

        // 6. Minimum Bathrooms
        if (! empty($filters['bathrooms']) && is_numeric($filters['bathrooms'])) {
            $baths = (int) $filters['bathrooms'];
            $query->whereHas('details', function (Builder $detailsQuery) use ($baths) {
                $detailsQuery->where('baths', '>=', $baths);
            });
        }

        // 7. Cap Rate filter (Minimum %)
        if (isset($filters['min_cap_rate']) && is_numeric($filters['min_cap_rate']) && $filters['min_cap_rate'] > 0) {
            $minCapRate = (float) $filters['min_cap_rate'];
            $query->whereHas('metrics', function (Builder $metricsQuery) use ($minCapRate) {
                $metricsQuery->where('cap_rate', '>=', $minCapRate);
            });
        }

        // 8. 1031 Exchange Ready filter
        if (! empty($filters['is_1031'])) {
            $query->whereHas('metrics', function (Builder $metricsQuery) {
                $metricsQuery->where('is_1031_exchange_eligible', 1);
            });
        }

        // 9. Sorting
        $sortBy = $filters['sort_by'] ?? 'latest';
        switch ($sortBy) {
            case 'price_asc':
                $query->join('property_details', 'properties.id', '=', 'property_details.property_id')
                    ->orderBy('property_details.value', 'asc')
                    ->select('properties.*');
                break;
            case 'price_desc':
                $query->join('property_details', 'properties.id', '=', 'property_details.property_id')
                    ->orderBy('property_details.value', 'desc')
                    ->select('properties.*');
                break;
            case 'cap_rate_desc':
                $query->leftJoin('property_metrics', 'properties.id', '=', 'property_metrics.property_id')
                    ->orderByRaw('COALESCE(property_metrics.cap_rate, 0) DESC')
                    ->select('properties.*');
                break;
            case 'cash_flow_desc':
                $query->leftJoin('property_metrics', 'properties.id', '=', 'property_metrics.property_id')
                    ->orderByRaw('COALESCE(property_metrics.annual_cash_flow, 0) DESC')
                    ->select('properties.*');
                break;
            case 'oldest':
                $query->oldest('properties.created_at');
                break;
            case 'latest':
            default:
                $query->latest('properties.created_at');
                break;
        }

        $paginator = $query->paginate($perPage)->withQueryString();

        // Ensure all loaded properties have a slug
        foreach ($paginator->items() as $prop) {
            if (empty($prop->slug) && ! empty($prop->name)) {
                $prop->slug = PropertyModel::generateUniqueSlug($prop->name, $prop->id);
                $prop->saveQuietly();
            }
        }

        return $paginator;
    }

    /**
     * Backward-compatible alias for getPaginatedProperties
     */
    public function getPaginatedProperties(int $perPage = 9, array $filters = []): LengthAwarePaginator
    {
        return $this->getFilteredProperties($filters, $perPage);
    }

    /**
     * Log user/guest search behavior for preference learning and personalized recommendations
     */
    public function logUserSearch(array $filters, int $resultsCount): void
    {
        try {
            $meaningfulKeys = ['q', 'location', 'min_price', 'max_price', 'property_type', 'bedrooms', 'bathrooms', 'min_cap_rate', 'is_1031', 'sort_by'];
            $hasFilter = false;
            foreach ($meaningfulKeys as $k) {
                if (! empty($filters[$k])) {
                    $hasFilter = true;
                    break;
                }
            }

            if (! $hasFilter) {
                return;
            }

            UserSearchLog::create([
                'user_id' => auth()->id(),
                'session_id' => session()->getId(),
                'ip_address' => request()->ip(),
                'user_agent' => substr((string) request()->userAgent(), 0, 500),
                'keyword' => ! empty($filters['q']) ? substr($filters['q'], 0, 255) : null,
                'location' => ! empty($filters['location']) ? substr($filters['location'], 0, 255) : null,
                'min_price' => ! empty($filters['min_price']) ? (int) $filters['min_price'] : null,
                'max_price' => ! empty($filters['max_price']) ? (int) $filters['max_price'] : null,
                'property_type' => ! empty($filters['property_type']) ? substr($filters['property_type'], 0, 100) : null,
                'bedrooms' => ! empty($filters['bedrooms']) ? (int) $filters['bedrooms'] : null,
                'bathrooms' => ! empty($filters['bathrooms']) ? (int) $filters['bathrooms'] : null,
                'min_cap_rate' => ! empty($filters['min_cap_rate']) ? (float) $filters['min_cap_rate'] : null,
                'is_1031' => ! empty($filters['is_1031']) ? (bool) $filters['is_1031'] : null,
                'sort_by' => ! empty($filters['sort_by']) ? substr($filters['sort_by'], 0, 50) : null,
                'filters_payload' => array_filter($filters, fn ($v) => $v !== null && $v !== ''),
                'results_count' => $resultsCount,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed to log user search activity: ' . $e->getMessage());
        }
    }

    /**
     * Get personalized property recommendations based on user's search history, favorites, or high yield
     */
    public function getPersonalizedRecommendations(?User $user = null, int $limit = 3): Collection
    {
        $userId = $user ? $user->id : auth()->id();
        $sessionId = session()->getId();

        // 1. Analyze recent search logs
        $recentSearches = UserSearchLog::where(function ($q) use ($userId, $sessionId) {
            if ($userId) {
                $q->where('user_id', $userId);
            } else {
                $q->where('session_id', $sessionId);
            }
        })
            ->latest()
            ->take(10)
            ->get();

        $preferredLocations = $recentSearches->pluck('location')->filter()->values()->all();
        $preferredTypes = $recentSearches->pluck('property_type')->filter()->values()->all();
        $minCapRates = $recentSearches->pluck('min_cap_rate')->filter()->values()->all();
        $avgMinCap = ! empty($minCapRates) ? array_sum($minCapRates) / count($minCapRates) : 0;

        $query = PropertyModel::with(['propertyImage', 'propertyAddress', 'propertyMetrics', 'propertyDetails']);

        if (! empty($preferredLocations)) {
            $query->whereHas('address', function ($q) use ($preferredLocations) {
                $q->whereIn('city', $preferredLocations)
                    ->orWhereIn('state', $preferredLocations);
            });
        } elseif (! empty($preferredTypes)) {
            $query->whereHas('details', function ($q) use ($preferredTypes) {
                $q->whereIn('type', $preferredTypes);
            });
        } elseif ($avgMinCap > 0) {
            $query->whereHas('metrics', function ($q) use ($avgMinCap) {
                $q->where('cap_rate', '>=', $avgMinCap);
            });
        }

        $recommendations = $query->take($limit)->get();

        // Fallback: If not enough results, fetch top cap rate properties
        if ($recommendations->count() < $limit) {
            $existingIds = $recommendations->pluck('id')->all();
            $fallback = PropertyModel::with(['propertyImage', 'propertyAddress', 'propertyMetrics', 'propertyDetails'])
                ->whereNotIn('properties.id', $existingIds)
                ->leftJoin('property_metrics', 'properties.id', '=', 'property_metrics.property_id')
                ->orderByRaw('COALESCE(property_metrics.cap_rate, 0) DESC')
                ->select('properties.*')
                ->take($limit - $recommendations->count())
                ->get();

            $recommendations = $recommendations->concat($fallback);
        }

        return $recommendations;
    }

    /**
     * Get distinct metadata for filter dropdown options
     */
    public function getFilterOptions(): array
    {
        return Cache::remember('property_filter_options', self::CACHE_TTL_SECONDS, function () {
            $cities = PropertyAddress::select('city')->distinct()->whereNotNull('city')->where('city', '!=', '')->pluck('city')->all();
            $states = PropertyAddress::select('state')->distinct()->whereNotNull('state')->where('state', '!=', '')->pluck('state')->all();
            $types = PropertyDetails::select('type')->distinct()->whereNotNull('type')->where('type', '!=', '')->pluck('type')->all();

            return [
                'cities' => array_values(array_filter($cities)),
                'states' => array_values(array_filter($states)),
                'types' => array_values(array_filter($types)),
            ];
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

        Cache::forget('property_filter_options');

        for ($page = 1; $page <= 20; $page++) {
            Cache::forget("investor_properties_page_{$page}_limit_9");
        }
    }
}
