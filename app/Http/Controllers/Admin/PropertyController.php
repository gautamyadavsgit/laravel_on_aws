<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PropertyRequest;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Services\PropertyMediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PropertyController extends Controller
{
    public function __construct(private readonly PropertyMediaService $mediaService) {}

    public function index(): View
    {
        $properties = Property::with('images')
            ->latest()
            ->paginate(15)
            ->onEachSide(2)
            ->withQueryString();

        return view('admin.properties.index', ['properties' => $properties]);
    }

    public function create(): View
    {
        return view('admin.properties.create');
    }

    public function store(PropertyRequest $request): RedirectResponse
    {
        $property = Property::create(
            $request->propertyAttributes() + ['admin_id' => auth('admin')->id()]
        );

        $this->mediaService->uploadPropertyImages($property, (array) $request->file('property_images'));

        return redirect()->route('admin.properties.index')->with('success', 'Property added successfully.');
    }

    public function show(Property $property): View
    {
        $property->load('images');

        return view('admin.properties.show', ['property' => $property, 'pagetitle' => $property->name]);
    }

    public function edit(Property $property): View
    {
        $property->load('images');

        return view('admin.properties.edit', ['property' => $property]);
    }

    public function update(PropertyRequest $request, Property $property): RedirectResponse
    {
        $property->update($request->propertyAttributes());

        $this->mediaService->uploadPropertyImages($property, (array) $request->file('property_images'));

        return redirect()->back()->with('success', 'Property updated successfully.');
    }

    public function destroyImage(Property $property, PropertyImage $image): RedirectResponse
    {
        if (! $this->mediaService->deletePropertyImage($image)) {
            return redirect()->back()->withErrors(['property_images' => 'A property needs at least one photo. Upload another before removing this one.']);
        }

        return redirect()->back()->with('success', 'Photo removed.');
    }

    public function destroy(Property $property): RedirectResponse
    {
        $property->delete();

        return redirect()->back()->with('success', 'Property deleted successfully');
    }
}
