<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListPropertiesRequest;
use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PropertyController extends Controller
{
    /**
     * Browse properties with multi-criteria filtering.
     */
    public function index(ListPropertiesRequest $request): View
    {
        $filters = $request->filters();

        $properties = Property::with('images')
            ->filter($filters)
            ->sortedBy($filters['sort_by'] ?? null)
            ->paginate(9, page: (int) ($request->page() ?? 1))
            ->appends($filters);

        return view('frontend.properties.index', [
            'properties' => $properties,
            'filters' => $filters,
            'filterOptions' => Property::filterOptions(),
            'favoriteIds' => auth()->user()?->favoritePropertyIds() ?? [],
        ]);
    }

    /**
     * Show a property by slug (/property/{slug}); a numeric id is redirected to the canonical slug URL.
     */
    public function show(string $slug): View|RedirectResponse
    {
        $property = Property::with('images')->where('slug', $slug)->first();

        if (! $property && ctype_digit($slug)) {
            $property = Property::with('images')->find($slug);

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

        $query = Property::with('images');
        $property = $propertyId ? $query->find($propertyId) : $query->first();

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
