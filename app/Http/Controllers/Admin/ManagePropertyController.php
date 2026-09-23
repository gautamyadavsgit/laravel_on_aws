<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminAddPropertyRequest;
use App\Http\Requests\Admin\AdminUpdateAddressRequest;
use App\Http\Requests\Admin\UpdatePropertyDetailsRequest;
use App\Http\Requests\Admin\UpdatePropertyDocumentRequest;
use App\Http\Requests\Admin\UpdatePropertyOfferingRequest;
use App\Http\Requests\Admin\UpdatePropertyRequest;
use App\Models\PropertyModel;
use App\Services\PropertyMediaService;
use App\Services\PropertyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class ManagePropertyController extends Controller
{
    protected PropertyMediaService $mediaService;

    protected PropertyService $propertyService;

    public function __construct(PropertyMediaService $mediaService, PropertyService $propertyService)
    {
        $this->mediaService = $mediaService;
        $this->propertyService = $propertyService;
    }

    /**
     * Display a listing of properties.
     */
    public function index(): View
    {
        $property = PropertyModel::with([
            'propertyImage',
            'propertyDetails',
            'propertyAddress',
            'propertyOffering',
            'propertyDocumentModel',
        ])
            ->latest()
            ->paginate(15)
            ->onEachSide(2)
            ->withQueryString();

        return view('admin.properties.properties')->with(compact('property'));
    }

    /**
     * Show the form for creating a new property.
     */
    public function create(): View
    {
        return view('admin.properties.add_property');
    }

    /**
     * Store a newly created property in storage.
     */
    public function store(AdminAddPropertyRequest $request): RedirectResponse
    {
        $data = Arr::except($request->validated(), ['property_images']);
        $data['admin_id'] = auth('admin')->id();
        $property = PropertyModel::create($data);

        // Handle file uploads with PropertyMediaService / Storage facade
        if ($request->hasFile('property_images')) {
            $this->mediaService->uploadPropertyImages($property, (array) $request->file('property_images'));
        }

        $this->propertyService->clearPropertiesCache();

        return redirect(route('admin.manage-property.edit-address', ['id' => $property->id]))
            ->with('success', 'Property added successfully.');
    }

    /**
     * Display the specified property.
     */
    public function show(string $id): View
    {
        $property = PropertyModel::with('propertyImage', 'propertyDocumentModel')->findOrFail($id);
        $pagetitle = $property->name ?? 'Property Details';

        return view('admin.properties.property')->with(['property' => $property, 'pagetitle' => $pagetitle]);
    }

    /**
     * Show the form for editing the specified property.
     */
    public function edit(string $id): View
    {
        $property = PropertyModel::findOrFail($id);

        return view('admin.properties.edit_properties')->with(['property' => $property, 'property_id' => $id]);
    }

    /**
     * Update the specified property in storage.
     */
    public function update(UpdatePropertyRequest $request, string $id): RedirectResponse
    {
        $data = Arr::except($request->validated(), ['property_images']);
        $property = PropertyModel::findOrFail($id);
        $oldSlug = $property->slug;

        $property->update($data);

        // Handle file uploads with PropertyMediaService / Storage facade
        if ($request->hasFile('property_images')) {
            $this->mediaService->uploadPropertyImages($property, (array) $request->file('property_images'));
        }

        if ($oldSlug && $oldSlug !== $property->slug) {
            $this->propertyService->clearPropertiesCache($oldSlug);
        }
        $this->propertyService->clearPropertiesCache($property->slug);

        return redirect()->back()->with('success', 'Property updated successfully.');
    }

    /**
     * Remove the specified property from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $property = PropertyModel::findOrFail($id);

        $slug = $property->slug;
        $property->delete();
        $this->propertyService->clearPropertiesCache($slug);

        return redirect()->back()->with('success', 'Property deleted successfully');
    }

    // ==========================================
    // Property Sub-Entity Wizard Steps
    // ==========================================

    public function editAddress(Request $request, string $id): View
    {
        $property = PropertyModel::findOrFail($id);
        $propertyAddress = $property->propertyAddress;

        return view('admin.properties.edit_address')->with(['property' => $property, 'propertyAddress' => $propertyAddress, 'property_id' => $id]);
    }

    public function updateAddress(AdminUpdateAddressRequest $request, string $id): RedirectResponse
    {
        $data = $request->validated();
        $property = PropertyModel::findOrFail($id);

        $propertyAddress = $property->propertyAddress;

        if ($propertyAddress) {
            $propertyAddress->update($data);
        } else {
            $property->propertyAddress()->create($data);
        }

        $this->propertyService->clearPropertiesCache($property->slug);

        return redirect(route('admin.manage-property.edit-details', ['id' => $id]))->with('success', 'Address updated successfully.');
    }

    public function editDetails(Request $request, string $id): View
    {
        $property = PropertyModel::findOrFail($id);
        $propertyDetails = $property->propertyDetails;

        return view('admin.properties.edit_details')->with(['property' => $property, 'propertyDetails' => $propertyDetails, 'property_id' => $id]);
    }

    public function updateDetails(UpdatePropertyDetailsRequest $request, string $id): RedirectResponse
    {
        $data = $request->validated();
        $property = PropertyModel::findOrFail($id);

        $propertyDetails = $property->propertyDetails;

        if ($propertyDetails) {
            $propertyDetails->update($data);
        } else {
            $property->propertyDetails()->create($data);
        }

        $this->propertyService->clearPropertiesCache($property->slug);

        return redirect(route('admin.manage-property.edit-property-offerings', ['id' => $id]))->with('success', 'Details updated successfully.');
    }

    public function editOfferings(Request $request, string $id): View
    {
        $property = PropertyModel::findOrFail($id);
        $propertyOffering = $property->propertyOffering;

        return view('admin.properties.edit_property_offering')->with(['property' => $property, 'propertyOffering' => $propertyOffering, 'property_id' => $id]);
    }

    public function updateOfferings(UpdatePropertyOfferingRequest $request, string $id): RedirectResponse
    {
        $data = $request->validated();
        $property = PropertyModel::findOrFail($id);

        $propertyOffering = $property->propertyOffering;

        if ($propertyOffering) {
            $propertyOffering->update($data);
        } else {
            $property->propertyOffering()->create($data);
        }

        $this->propertyService->clearPropertiesCache($property->slug);

        return redirect(route('admin.manage-property.edit-property-documents', ['id' => $id]))->with('success', 'Offerings updated successfully.');
    }

    public function editDocuments(Request $request, string $id): View
    {
        $property = PropertyModel::findOrFail($id);
        $propertyDocumentModel = $property->propertyDocumentModel;

        return view('admin.properties.edit_property_documents')->with(['property' => $property, 'propertyDocumentModel' => $propertyDocumentModel, 'property_id' => $id]);
    }

    public function updateDocuments(UpdatePropertyDocumentRequest $request, string $id): RedirectResponse
    {
        $data = $request->validated();
        $filteredData = array_filter($data);
        $property = PropertyModel::findOrFail($id);

        // Upload documents via PropertyMediaService / Storage facade
        $this->mediaService->uploadDocuments($property, $request->allFiles(), $filteredData);
        $this->propertyService->clearPropertiesCache($property->slug);

        return redirect(route('manage-property.index'))->with('success', 'Documents updated successfully.');
    }
}
