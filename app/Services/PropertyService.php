<?php

namespace App\Services;

use App\Models\PropertyAddress;
use App\Models\PropertyDetails;
use App\Models\PropertyModel;
use App\Models\User;
use App\Models\UserSearchLog;
use Illuminate\Contracts\Cache\Repository;
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
     * Listing pages are cached per URL for a shorter window (5 minutes)
     */
    protected const LISTING_CACHE_TTL_SECONDS = 300;

    /**
     * Key holding the listing cache generation; changing it orphans every cached listing URL
     */
    protected const LISTING_VERSION_KEY = 'property_listing_version';

    protected function cache(): Repository
    {
        return Cache::store();
    }

    /**
     * URL-based cache of a listing page: one entry per normalised URL (path + filters + page)
     */
    public function getCachedFilteredProperties(array $filters = [], int $perPage = 9): LengthAwarePaginator
    {
        // Array values (e.g. ?q[]=x) can't be keyed safely, so skip the cache for them
        foreach ($filters as $value) {
            if ($value !== null && ! is_scalar($value)) {
                return $this->getFilteredProperties($filters, $perPage);
            }
        }

        $params = array_filter($filters, fn ($v) => $v !== null && $v !== '');
        ksort($params);
        $params['page'] = max(1, (int) request()->query('page', 1));
        $params['per_page'] = $perPage;

        $version = $this->cache()->rememberForever(self::LISTING_VERSION_KEY, fn () => (string) microtime(true));
        $cacheKey = 'property_listing_'.sha1($version.'|'.request()->url().'?'.http_build_query($params));

        return $this->cache()->remember(
            $cacheKey,
            self::LISTING_CACHE_TTL_SECONDS,
            fn () => $this->getFilteredProperties($filters, $perPage)
        );
    }

    /**
     * Get paginated properties with dynamic multi-criteria filtering
     */
    public function getFilteredProperties(array $filters = [], int $perPage = 9): LengthAwarePaginator
    {
        $query = PropertyModel::with([
            'images',
            'address',
            'details',
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

        // 7. Sorting
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
            case 'oldest':
                $query->oldest('properties.created_at');
                break;
            case 'latest':
            default:
                $query->latest('properties.created_at');
                break;
        }

        $paginator = $query->paginate($perPage)->withQueryString();

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
            $meaningfulKeys = ['q', 'location', 'min_price', 'max_price', 'property_type', 'bedrooms', 'bathrooms', 'sort_by'];
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
                'sort_by' => ! empty($filters['sort_by']) ? substr($filters['sort_by'], 0, 50) : null,
                'filters_payload' => array_filter($filters, fn ($v) => $v !== null && $v !== ''),
                'results_count' => $resultsCount,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed to log user search activity: '.$e->getMessage());
        }
    }

    /**
     * Get personalized property recommendations based on user's search history, favorites, or newest listings
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

        $query = PropertyModel::with(['images', 'address', 'details']);

        if (! empty($preferredLocations)) {
            $query->whereHas('address', function ($q) use ($preferredLocations) {
                $q->whereIn('city', $preferredLocations)
                    ->orWhereIn('state', $preferredLocations);
            });
        } elseif (! empty($preferredTypes)) {
            $query->whereHas('details', function ($q) use ($preferredTypes) {
                $q->whereIn('type', $preferredTypes);
            });
        }

        $recommendations = $query->take($limit)->get();

        // Fallback: If not enough results, fill with the newest properties
        if ($recommendations->count() < $limit) {
            $existingIds = $recommendations->pluck('id')->all();
            $fallback = PropertyModel::with(['images', 'address', 'details'])
                ->whereNotIn('id', $existingIds)
                ->latest()
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
        return $this->cache()->remember('property_filter_options', self::CACHE_TTL_SECONDS, function () {
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
     * Get property by its slug with per-property caching and eager-loaded relations
     */
    public function getPropertyBySlug(string $slug): ?PropertyModel
    {
        return $this->cache()->remember($this->detailCacheKey($slug), self::CACHE_TTL_SECONDS, function () use ($slug) {
            return PropertyModel::with([
                'images',
                'documents',
                'address',
                'details',
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
            'images',
            'documents',
            'address',
            'details',
        ]);

        $property = $id ? $query->find($id) : $query->first();

        return $property;
    }

    /**
     * Invalidate the property's own detail cache, plus every cached listing URL
     * (the database store has no tags, so listings are orphaned by bumping the version)
     */
    public function clearPropertiesCache(?string $slug = null): void
    {
        if ($slug) {
            $this->cache()->forget($this->detailCacheKey($slug));
        }

        $this->cache()->forget('property_filter_options');
        $this->cache()->forever(self::LISTING_VERSION_KEY, (string) microtime(true));
    }

    /**
     * Slugs come from the URL (and can be up to 255 chars), so hash them to keep the key within the cache table's key column
     */
    protected function detailCacheKey(string $slug): string
    {
        return 'property_detail_'.md5($slug);
    }
}
