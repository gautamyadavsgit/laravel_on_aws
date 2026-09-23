<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePropertyAddressRequest;
use App\Models\Property;
use App\Services\PropertyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PropertyAddressController extends Controller
{
    public function __construct(private readonly PropertyService $propertyService) {}

    public function edit(Property $property): View
    {
        return view('admin.properties.edit-address', ['property' => $property]);
    }

    public function update(UpdatePropertyAddressRequest $request, Property $property): RedirectResponse
    {
        $property->address()->updateOrCreate([], $request->validated());

        $this->propertyService->clearPropertiesCache($property->slug);

        return redirect()->route('admin.properties.details.edit', $property)->with('success', 'Address updated successfully.');
    }
}
