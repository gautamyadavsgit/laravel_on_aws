<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminAddPropertyRequest;
use App\Http\Requests\Admin\AdminUpdateAddressRequest;
use App\Http\Requests\Admin\UpdatePropertyAmenitiesRequest;
use App\Http\Requests\Admin\UpdatePropertyDetailsRequest;
use App\Http\Requests\Admin\UpdatePropertyDocumentRequest;
use App\Http\Requests\Admin\UpdatePropertyFloorPlanRequest;
use App\Http\Requests\Admin\UpdatePropertyMetricsRequest;
use App\Http\Requests\Admin\UpdatePropertyOfferingRequest;
use App\Http\Requests\Admin\UpdatePropertyRequest;
use App\Models\PropertyModel;
use App\Services\PropertyMediaService;
use App\Services\PropertyMetricsService;
use App\Services\PropertyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'propertyAmenities',
            'propertyFloorplan',
            'propertyDocumentModel',
            'propertyMetrics',
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
        $data = $request->validated();
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
        $property = PropertyModel::with('propertyImage', 'propertyFloorplan', 'propertyDocumentModel')->findOrFail($id);
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
        $data = $request->validated();
        $property = PropertyModel::findOrFail($id);

        $property->update($data);

        // Handle file uploads with PropertyMediaService / Storage facade
        if ($request->hasFile('property_images')) {
            $this->mediaService->uploadPropertyImages($property, (array) $request->file('property_images'));
        }

        $this->propertyService->clearPropertiesCache($property->slug);

        return redirect()->back()->with('success', 'Property updated successfully.');
    }

    /**
     * Remove the specified property from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $property = PropertyModel::find($id);

        if ($property) {
            $slug = $property->slug;
            $property->delete();
            $this->propertyService->clearPropertiesCache($slug);
        }

        return redirect()->back()->with('success', 'Property deleted successfully');
    }

    // ==========================================
    // Property Sub-Entity Wizard Steps
    // ==========================================

    public function editAddress(Request $request, string $id): View
    {
        $property = PropertyModel::findOrFail($id);
        $propertyAddress = $property->propertyAddress;

        return view('admin.properties.edit_address')->with(['propertyAddress' => $propertyAddress, 'property_id' => $id]);
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

        return view('admin.properties.edit_details')->with(['propertyDetails' => $propertyDetails, 'property_id' => $id]);
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

        return redirect(route('admin.manage-property.edit-amenities', ['id' => $id]))->with('success', 'Details updated successfully.');
    }

    public function editAmenities(Request $request, string $id): View
    {
        $property = PropertyModel::findOrFail($id);
        $propertyAmenities = $property->propertyAmenities;

        return view('admin.properties.edit_amenities')->with(['propertyAmenities' => $propertyAmenities, 'property_id' => $id]);
    }

    public function updateAmenities(UpdatePropertyAmenitiesRequest $request, string $id): RedirectResponse
    {
        $data = $request->validated();
        $rawAmenities = $data['property_amenities'] ?? [];
        $property = PropertyModel::findOrFail($id);

        // Normalize amenities array from CSV string or array
        if (is_string($rawAmenities)) {
            $rawAmenities = array_filter(explode(',', $rawAmenities));
        }

        $formattedData = [];
        foreach ($rawAmenities as $amenity) {
            if (! empty(trim((string) $amenity))) {
                $formattedData[] = ['property_amenities' => trim((string) $amenity)];
            }
        }

        $property->propertyAmenities()->delete();
        if (! empty($formattedData)) {
            $property->propertyAmenities()->createMany($formattedData);
        }

        $this->propertyService->clearPropertiesCache($property->slug);

        return redirect(route('admin.manage-property.edit-floorplan', ['id' => $id]))->with('success', 'Amenities updated successfully.');
    }

    public function editFloorplan(Request $request, string $id): View
    {
        $property = PropertyModel::findOrFail($id);
        $propertyFloorplan = $property->propertyFloorplan;

        return view('admin.properties.edit_floorplan')->with(['propertyFloorplan' => $propertyFloorplan, 'property_id' => $id]);
    }

    public function updateFloorplan(UpdatePropertyFloorPlanRequest $request, string $id): RedirectResponse
    {
        $data = $request->validated();
        $filteredData = array_filter($data);
        $property = PropertyModel::findOrFail($id);

        // Upload floorplans via PropertyMediaService / Storage facade
        $this->mediaService->uploadFloorplans($property, $request->allFiles(), $filteredData);
        $this->propertyService->clearPropertiesCache($property->slug);

        return redirect(route('admin.manage-property.edit-property-offerings', ['id' => $id]))->with('success', 'Floor Plan updated successfully.');
    }

    public function editOfferings(Request $request, string $id): View
    {
        $property = PropertyModel::findOrFail($id);
        $propertyOffering = $property->propertyOffering;

        return view('admin.properties.edit_property_offering')->with(['propertyOffering' => $propertyOffering, 'property_id' => $id]);
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

        return view('admin.properties.edit_property_documents')->with(['propertyDocumentModel' => $propertyDocumentModel, 'property_id' => $id]);
    }

    public function updateDocuments(UpdatePropertyDocumentRequest $request, string $id): RedirectResponse
    {
        $data = $request->validated();
        $filteredData = array_filter($data);
        $property = PropertyModel::findOrFail($id);

        // Upload documents via PropertyMediaService / Storage facade
        $this->mediaService->uploadDocuments($property, $request->allFiles(), $filteredData);
        $this->propertyService->clearPropertiesCache($property->slug);

        return redirect(route('admin.manage-property.edit-property-metrics', ['id' => $id]))->with('success', 'Documents updated successfully.');
    }

    public function editMetrics(Request $request, string $id): View
    {
        $property = PropertyModel::with(['propertyDetails', 'propertyOffering', 'propertyMetrics'])->findOrFail($id);

        $propertyMetrics = $property->propertyMetrics;
        if (! $propertyMetrics) {
            $propertyMetrics = PropertyMetricsService::syncForProperty($property);
        }

        return view('admin.properties.edit_property_metrics')->with([
            'property' => $property,
            'propertyMetrics' => $propertyMetrics,
            'property_id' => $id,
        ]);
    }

    public function updateMetrics(UpdatePropertyMetricsRequest $request, string $id): RedirectResponse
    {
        $data = $request->validated();
        $property = PropertyModel::findOrFail($id);

        PropertyMetricsService::syncForProperty($property, $data);
        $this->propertyService->clearPropertiesCache($property->slug);

        return redirect(route('admin.manage-property.index'))->with('success', 'Property metrics updated successfully.');
    }

    // ==========================================
    // Backward Compatibility Method Aliases
    // ==========================================

    public function edit_address(Request $request, string $id): View
    {
        return $this->editAddress($request, $id);
    }

    public function update_address(AdminUpdateAddressRequest $request, string $id): RedirectResponse
    {
        return $this->updateAddress($request, $id);
    }

    public function edit_property_details(Request $request, string $id): View
    {
        return $this->editDetails($request, $id);
    }

    public function update_property_details(UpdatePropertyDetailsRequest $request, string $id): RedirectResponse
    {
        return $this->updateDetails($request, $id);
    }

    public function edit_amenities(Request $request, string $id): View
    {
        return $this->editAmenities($request, $id);
    }

    public function update_aminities(UpdatePropertyAmenitiesRequest $request, string $id): RedirectResponse
    {
        return $this->updateAmenities($request, $id);
    }

    public function edit_floorplan(Request $request, string $id): View
    {
        return $this->editFloorplan($request, $id);
    }

    public function update_floorplan(UpdatePropertyFloorPlanRequest $request, string $id): RedirectResponse
    {
        return $this->updateFloorplan($request, $id);
    }

    public function edit_property_offerings(Request $request, string $id): View
    {
        return $this->editOfferings($request, $id);
    }

    public function update_property_offerings(UpdatePropertyOfferingRequest $request, string $id): RedirectResponse
    {
        return $this->updateOfferings($request, $id);
    }

    public function edit_property_documents(Request $request, string $id): View
    {
        return $this->editDocuments($request, $id);
    }

    public function update_property_documents(UpdatePropertyDocumentRequest $request, string $id): RedirectResponse
    {
        return $this->updateDocuments($request, $id);
    }

    public function edit_property_metrics(Request $request, string $id): View
    {
        return $this->editMetrics($request, $id);
    }

    public function update_property_metrics(UpdatePropertyMetricsRequest $request, string $id): RedirectResponse
    {
        return $this->updateMetrics($request, $id);
    }
}
