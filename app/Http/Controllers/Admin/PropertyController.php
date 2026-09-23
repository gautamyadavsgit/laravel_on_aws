<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePropertyRequest;
use App\Http\Requests\Admin\UpdatePropertyRequest;
use App\Models\Property;
use App\Services\PropertyMediaService;
use App\Services\PropertyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class PropertyController extends Controller
{
    public function __construct(
        private readonly PropertyMediaService $mediaService,
        private readonly PropertyService $propertyService,
    ) {}

    public function index(): View
    {
        $properties = Property::with(['images', 'details', 'address', 'offering', 'documents'])
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

    public function store(StorePropertyRequest $request): RedirectResponse
    {
        $attributes = Arr::except($request->validated(), ['property_images']);
        $attributes['admin_id'] = auth('admin')->id();
        $property = Property::create($attributes);

        if ($request->hasFile('property_images')) {
            $this->mediaService->uploadPropertyImages($property, (array) $request->file('property_images'));
        }

        $this->propertyService->clearPropertiesCache();

        return redirect()->route('admin.properties.address.edit', $property)
            ->with('success', 'Property added successfully.');
    }

    public function show(Property $property): View
    {
        $property->load(['images', 'documents']);

        return view('admin.properties.show', ['property' => $property, 'pagetitle' => $property->name]);
    }

    public function edit(Property $property): View
    {
        return view('admin.properties.edit', ['property' => $property]);
    }

    public function update(UpdatePropertyRequest $request, Property $property): RedirectResponse
    {
        $oldSlug = $property->slug;

        $property->update(Arr::except($request->validated(), ['property_images']));

        if ($request->hasFile('property_images')) {
            $this->mediaService->uploadPropertyImages($property, (array) $request->file('property_images'));
        }

        if ($oldSlug && $oldSlug !== $property->slug) {
            $this->propertyService->clearPropertiesCache($oldSlug);
        }
        $this->propertyService->clearPropertiesCache($property->slug);

        return redirect()->back()->with('success', 'Property updated successfully.');
    }

    public function destroy(Property $property): RedirectResponse
    {
        $slug = $property->slug;
        $property->delete();
        $this->propertyService->clearPropertiesCache($slug);

        return redirect()->back()->with('success', 'Property deleted successfully');
    }
}
