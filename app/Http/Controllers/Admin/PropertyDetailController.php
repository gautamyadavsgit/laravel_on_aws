<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePropertyDetailRequest;
use App\Models\Property;
use App\Services\PropertyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PropertyDetailController extends Controller
{
    public function __construct(private readonly PropertyService $propertyService) {}

    public function edit(Property $property): View
    {
        return view('admin.properties.edit-details', ['property' => $property]);
    }

    public function update(UpdatePropertyDetailRequest $request, Property $property): RedirectResponse
    {
        $property->details()->updateOrCreate([], $request->validated());

        $this->propertyService->clearPropertiesCache($property->slug);

        return redirect()->route('admin.properties.offering.edit', $property)->with('success', 'Details updated successfully.');
    }
}
