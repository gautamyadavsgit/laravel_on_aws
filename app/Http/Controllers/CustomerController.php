<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Services\PropertyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
        $filters = $this->sanitizeListingFilters($request);

        // Views, pagination links and the listing cache read the raw query string, so leave only vetted values in it
        $request->query->replace($filters + array_filter(['page' => $this->sanitizePage($request->query('page'))]));

        $properties = $this->propertyService->getCachedFilteredProperties($filters, 9);
        $this->propertyService->logUserSearch($filters, $properties->total());

        $recommendations = $this->propertyService->getPersonalizedRecommendations(auth()->user(), 3);
        $filterOptions = $this->propertyService->getFilterOptions();

        $favoriteIds = auth()->user()?->favoritePropertyIds() ?? [];

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
            if (! $property && ctype_digit($slug)) {
                $property = $this->propertyService->getPropertyById($slug);
                if ($property && ! empty($property->slug)) {
                    return redirect()->route('property.singlepage', ['slug' => $property->slug], 301);
                }
            }

            if (! $property) {
                abort(404, 'The requested real estate asset could not be found.');
            }

            return $this->renderSinglePage($property);
        }

        // 2. Legacy fallback for /property_singlepage?id=X
        $propertyId = $request->query('id');
        if ($propertyId !== null && (! is_string($propertyId) || ! ctype_digit($propertyId))) {
            abort(404, 'The requested real estate asset could not be found.');
        }

        $featuredProperty = $this->propertyService->getPropertyById($propertyId);

        if (! $featuredProperty) {
            abort(404, 'The requested real estate asset could not be found.');
        }

        if (! empty($featuredProperty->slug)) {
            return redirect()->route('property.singlepage', ['slug' => $featuredProperty->slug], 301);
        }

        return $this->renderSinglePage($featuredProperty);
    }

    protected function renderSinglePage(Property $property): View
    {
        return view('frontend.property_singlepage', [
            'featuredProperty' => $property,
            'isFavorited' => auth()->user()?->hasFavorited($property->id) ?? false,
        ]);
    }

    /**
     * Keep only known listing filters, coerced to safe scalar values.
     * Anything malformed (arrays, non-numeric numbers, unknown sort keys) is dropped rather than erroring.
     *
     * @return array<string, string>
     */
    protected function sanitizeListingFilters(Request $request): array
    {
        $filters = [];

        foreach (['q' => 100, 'location' => 100, 'property_type' => 100] as $key => $maxLength) {
            $value = $request->query($key);
            if (is_string($value) && ($value = trim($value)) !== '') {
                $filters[$key] = mb_substr($value, 0, $maxLength);
            }
        }

        foreach (['min_price' => 100000000000, 'max_price' => 100000000000, 'bedrooms' => 100, 'bathrooms' => 100] as $key => $max) {
            $value = $request->query($key);
            if (is_string($value) && is_numeric($value) && (float) $value > 0) {
                $filters[$key] = (string) (int) min((float) $value, $max);
            }
        }

        $sortBy = $request->query('sort_by');
        if (is_string($sortBy) && in_array($sortBy, ['latest', 'oldest', 'price_asc', 'price_desc'], true)) {
            $filters['sort_by'] = $sortBy;
        }

        return $filters;
    }

    /**
     * A page number is only kept when it is a plain positive integer.
     */
    protected function sanitizePage(mixed $page): ?string
    {
        return is_string($page) && ctype_digit($page) && (int) $page >= 1 && strlen($page) <= 6 ? $page : null;
    }
}
