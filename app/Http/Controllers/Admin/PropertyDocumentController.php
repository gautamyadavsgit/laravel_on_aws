<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePropertyDocumentRequest;
use App\Models\Property;
use App\Services\PropertyMediaService;
use App\Services\PropertyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PropertyDocumentController extends Controller
{
    public function __construct(
        private readonly PropertyMediaService $mediaService,
        private readonly PropertyService $propertyService,
    ) {}

    public function edit(Property $property): View
    {
        return view('admin.properties.edit-documents', ['property' => $property]);
    }

    public function update(UpdatePropertyDocumentRequest $request, Property $property): RedirectResponse
    {
        $this->mediaService->uploadDocuments($property, $request->allFiles(), array_filter($request->validated()));

        $this->propertyService->clearPropertiesCache($property->slug);

        return redirect()->route('admin.properties.index')->with('success', 'Documents updated successfully.');
    }
}
