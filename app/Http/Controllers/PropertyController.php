<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListPropertiesRequest;
use App\Models\Property;
use App\Services\PropertyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PropertyController extends Controller
{
    public function __construct(private readonly PropertyService $propertyService) {}

    /**
     * Browse properties with multi-criteria filtering and personalized recommendations.
     */
    public function index(ListPropertiesRequest $request): View
    {
        $filters = $request->filters();

        // Views, pagination links and the listing cache read the current request's raw query string, and a
        // form request is a copy of it, so the vetted values are written back to the current request
        request()->query->replace($filters + array_filter(['page' => $request->page()]));

        $properties = $this->propertyService->getCachedFilteredProperties($filters, 9);
        $this->propertyService->logUserSearch($filters, $properties->total());

        return view('frontend.properties.index', [
            'properties' => $properties,
            'filters' => $filters,
            'filterOptions' => $this->propertyService->getFilterOptions(),
            'recommendations' => $this->propertyService->getPersonalizedRecommendations(auth()->user(), 3),
            'favoriteIds' => auth()->user()?->favoritePropertyIds() ?? [],
        ]);
    }

    /**
     * Show a property by slug (/property/{slug}); a numeric id is redirected to the canonical slug URL.
     */
    public function show(string $slug): View|RedirectResponse
    {
        $property = $this->propertyService->getPropertyBySlug($slug);

        if (! $property && ctype_digit($slug)) {
            $property = $this->propertyService->getPropertyById($slug);

            if ($property?->slug) {
                return redirect()->route('properties.show', ['slug' => $property->slug], 301);
            }
        }

        abort_if(! $property, 404, 'The requested real estate asset could not be found.');

        return $this->renderProperty($property);
    }

    /**
     * Legacy /property_singlepage?id=X links redirect to the canonical slug URL.
     */
    public function showLegacy(Request $request): View|RedirectResponse
    {
        $propertyId = $request->query('id');

        abort_if($propertyId !== null && (! is_string($propertyId) || ! ctype_digit($propertyId)), 404, 'The requested real estate asset could not be found.');

        $property = $this->propertyService->getPropertyById($propertyId);

        abort_if(! $property, 404, 'The requested real estate asset could not be found.');

        if ($property->slug) {
            return redirect()->route('properties.show', ['slug' => $property->slug], 301);
        }

        return $this->renderProperty($property);
    }

    private function renderProperty(Property $property): View
    {
        return view('frontend.properties.show', [
            'property' => $property,
            'isFavorited' => auth()->user()?->hasFavorited($property->id) ?? false,
        ]);
    }
}
