<?php

namespace App\Http\Controllers;

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
     * Browse investor properties with Cache facade
     */
    public function investor(): View
    {
        $properties = $this->propertyService->getPaginatedProperties(9);

        return view('frontend.properties', compact('properties'));
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
