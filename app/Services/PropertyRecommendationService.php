<?php

namespace App\Services;

use App\Models\Property;
use App\Models\User;
use App\Models\UserSearchLog;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class PropertyRecommendationService
{
    /**
     * Log user/guest search behavior for preference learning and personalized recommendations
     */
    public function logSearch(array $filters, int $resultsCount): void
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
     * Recommend properties matching the visitor's recent searches, topped up with the newest listings
     */
    public function recommend(?User $user = null, int $limit = 3): Collection
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

        $query = Property::with(['images', 'address', 'details']);

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
            $fallback = Property::with(['images', 'address', 'details'])
                ->whereNotIn('id', $existingIds)
                ->latest()
                ->take($limit - $recommendations->count())
                ->get();

            $recommendations = $recommendations->concat($fallback);
        }

        return $recommendations;
    }
}
