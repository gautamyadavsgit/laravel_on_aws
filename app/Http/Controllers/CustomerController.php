<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

use App\Services\PropertyService;

class CustomerController extends Controller
{
    protected PropertyService $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    /**
     * Browse investor properties with multi-criteria filtering and personalized recommendations
     */
    public function investor(Request $request): View
    {
        $filters = $request->only([
            'q',
            'location',
            'min_price',
            'max_price',
            'property_type',
            'bedrooms',
            'bathrooms',
            'min_cap_rate',
            'is_1031',
            'sort_by',
        ]);

        $properties = $this->propertyService->getFilteredProperties($filters, 9);
        $this->propertyService->logUserSearch($filters, $properties->total());

        $recommendations = $this->propertyService->getPersonalizedRecommendations(auth()->user(), 3);
        $filterOptions = $this->propertyService->getFilterOptions();

        $favoriteIds = auth()->check()
            ? \App\Models\PropertyFavorite::where('user_id', auth()->id())->pluck('property_id')->toArray()
            : [];

        return view('frontend.properties', compact('properties', 'favoriteIds', 'filters', 'filterOptions', 'recommendations'));
    }

    /**
     * Real-time property single page resolver by slug (/property/{slug})
     */
    public function property_singlepage(Request $request, ?string $slug = null): View|RedirectResponse
    {
        // 1. If slug is explicitly provided in URL route:
        if ($slug) {
            $property = $this->propertyService->getPropertyBySlug($slug);

            // Fallback: If not found by slug, check if numeric ID was provided
            if (! $property && is_numeric($slug)) {
                $property = $this->propertyService->getPropertyById($slug);
                if ($property && ! empty($property->slug)) {
                    return redirect()->route('property.singlepage', ['slug' => $property->slug], 301);
                }
            }

            if (! $property) {
                abort(404, 'The requested real estate asset could not be found.');
            }

            return view('frontend.property_singlepage', ['featuredProperty' => $property]);
        }

        // 2. Legacy fallback for /property_singlepage?id=X
        $propertyId = $request->query('id');
        $featuredProperty = $this->propertyService->getPropertyById($propertyId);

        if ($featuredProperty && ! empty($featuredProperty->slug)) {
            return redirect()->route('property.singlepage', ['slug' => $featuredProperty->slug], 301);
        }
        return view('frontend.property_singlepage', compact('featuredProperty'));
    }
}
