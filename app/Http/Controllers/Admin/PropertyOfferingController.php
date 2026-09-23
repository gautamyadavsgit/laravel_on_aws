<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePropertyOfferingRequest;
use App\Models\Property;
use App\Services\PropertyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PropertyOfferingController extends Controller
{
    public function __construct(private readonly PropertyService $propertyService) {}

    public function edit(Property $property): View
    {
        return view('admin.properties.edit-offering', ['property' => $property]);
    }

    public function update(UpdatePropertyOfferingRequest $request, Property $property): RedirectResponse
    {
        $property->offering()->updateOrCreate([], $request->validated());

        $this->propertyService->clearPropertiesCache($property->slug);

        return redirect()->route('admin.properties.documents.edit', $property)->with('success', 'Offerings updated successfully.');
    }
}
