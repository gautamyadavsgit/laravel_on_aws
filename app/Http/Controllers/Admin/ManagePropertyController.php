<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminAddPropertyRequest;
use Illuminate\Http\Request;
use App\Models\PropertyModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\AdminUpdateAddressRequest;
use App\Http\Requests\Admin\UpdatePropertyAminitiesRequest;
use App\Http\Requests\Admin\UpdatePropertyDetailsRequest;
use App\Http\Requests\Admin\UpdatePropertyDocumentRequest;
use App\Http\Requests\Admin\UpdatePropertyFloorPlanRequest;
use App\Http\Requests\Admin\UpdatePropertyOfferingRequest;
use App\Http\Requests\Admin\UpdatePropertyRequest;
use App\Http\Requests\Admin\UpdatePropertyMetricsRequest;
use App\Services\PropertyMetricsService;

class ManagePropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.properties.add_property');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminAddPropertyRequest $request)
    {
        $data = $request->validated();
        $data['admin_id'] = auth('admin')->id();
        $property = PropertyModel::create($data);

        // Handle file uploads
        if ($request->hasFile('property_images')) {
            foreach ($request->file('property_images') as $file) {
                $url = $file->store('property_images', 'public');
                $files[] = ['property_image_key' => 'property_image', 'property_image_value' => $url];
            }
        }

        if (!empty($files)) {
            $property->propertyImage()->createMany($files);
        }

        return redirect(route('admin.manage-property.edit-address', ['id' => $property->id]))->with('success', 'Property added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $property = PropertyModel::with('propertyImage', 'propertyFloorplan', 'propertyDocumentModel')->find($id);
        // dd($property);
        $pagetitle = $property->name;
        return view('admin.properties.property')->with(['property' => $property, 'pagetitle' => $pagetitle]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $property = PropertyModel::find($id);
        return view('admin.properties.edit_properties')->with(['property' => $property, 'property_id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePropertyRequest $request, string $id)
    {
        $data = $request->validated();
        $property = PropertyModel::find($id);
        if ($property) {
            $property->update($data);
        } else {
            return;
        }

        // Handle file uploads
        if ($request->hasFile('property_images')) {
            foreach ($request->file('property_images') as $file) {
                $url = $file->store('property_images', 'public');
                $files[] = ['property_image_key' => 'property_image', 'property_image_value' => $url];
            }
        }

        if (!empty($files)) {
            $property->propertyImage()->createMany($files);
        }

        return redirect()->back()->with('success', 'Property updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $property = PropertyModel::find($id);

        if ($property) {
            $property->delete(); // Soft delete the entry
        }

        return redirect()->back()->with('success', 'Property deleted successfully');
    }

    public function edit_address(Request $request, string $id)
    {
        $property = PropertyModel::find($id);
        $propertyAddress = $property->propertyAddress;
        return view('admin.properties.edit_address')->with(['propertyAddress' => $propertyAddress, 'property_id' => $id]);
    }
    public function update_address(AdminUpdateAddressRequest $request, string $id)
    {
        $data = $request->validated();

        $property = PropertyModel::find($id);

        // Retrieve the existing property address
        $propertyAddress = $property->propertyAddress;

        // Check if propertyAddress exists
        if ($propertyAddress) {
            // Property address exists, update the existing record
            $propertyAddress->update($data);
        } else {
            // Property address does not exist, create a new record
            $property->propertyAddress()->create($data);
        }
        return redirect(route('admin.manage-property.edit-details', ['id' => $id]))->with('success', 'Address updated successfully.');

    }

    public function edit_property_details(Request $request, string $id)
    {
        $property = PropertyModel::find($id);
        $propertyDetails = $property->propertyDetails;
        return view('admin.properties.edit_details')->with(['propertyDetails' => $propertyDetails, 'property_id' => $id]);
    }
    public function update_property_details(UpdatePropertyDetailsRequest $request, string $id)
    {
        $data = $request->validated();

        $property = PropertyModel::find($id);

        // Retrieve the existing property address
        $propertyDetails = $property->propertyDetails;

        // Check if propertyAddress exists
        if ($propertyDetails) {
            // Property address exists, update the existing record
            $propertyDetails->update($data);
        } else {
            // Property address does not exist, create a new record
            $property->propertyDetails()->create($data);
        }
        return redirect(route('admin.manage-property.edit-amenities', ['id' => $id]))->with('success', 'details updated successfully.');

    }

    public function edit_amenities(Request $request, string $id)
    {
        $property = PropertyModel::find($id);
        $propertyAmenities = $property->propertyAmenities;
        return view('admin.properties.edit_amenities')->with(['propertyAmenities' => $propertyAmenities, 'property_id' => $id]);
    }
    public function update_aminities(UpdatePropertyAminitiesRequest $request, string $id)
    {
        $data = $request->validated();

        // Convert the comma-separated string to an array
        $propertyAmenitiesArray = explode(',', $data['property_amenities']);

        $propertyAmenitiesArray = array_filter($propertyAmenitiesArray);

        $property = PropertyModel::find($id);

        $propertyAmenities = $property->propertyAmenities;
        $newData = [];
        foreach ($propertyAmenitiesArray as $property_amenity) {
            $newData[] = ['property_amenities' => $property_amenity];
        }
        if (!$propertyAmenities->isEmpty()) {
            $propertyAmenities->each->delete();
        }
        $property->propertyAmenities()->createMany($newData);
        return redirect(route('admin.manage-property.edit-floorplan', ['id' => $id]))->with('success', 'Aminities updated successfully.');

    }

    public function edit_floorplan(Request $request, string $id)
    {
        $property = PropertyModel::find($id);
        $propertyFloorplan = $property->propertyFloorplan;

        return view('admin.properties.edit_floorplan')->with(['propertyFloorplan' => $propertyFloorplan, 'property_id' => $id]);
    }

    public function update_floorplan(UpdatePropertyFloorPlanRequest $request, string $id)
    {
        $data = $request->validated();
        // dd($data);
        $filteredData = array_filter($data);
        $property = PropertyModel::find($id);

        // Retrieve the existing property address
        $propertyFloorplan = $property->propertyFloorplan;
        // $arraykeys = array_keys($filteredData);
        foreach ($filteredData as $key => $value) {

            if ($request->hasFile($key)) {
                $url = $value->store('floorplan_images', 'public');
                $newData[] = ['key' => $key, 'value' => $url];
            }

        }
        if (!$propertyFloorplan->isEmpty()) {

            foreach ($propertyFloorplan as $model) {
                if (array_key_exists($model->key, $filteredData)) {

                    Storage::disk('public')->delete($model->value);
                    $model->delete();
                }
            }
            $property->propertyFloorplan()->createMany($newData);
        } else {
            $property->propertyFloorplan()->createMany($newData);
        }
        return redirect(route('admin.manage-property.edit-property-offerings', ['id' => $id]))->with('success', 'Floor Plan updated successfully.');
    }

    public function edit_property_offerings(Request $request, string $id)
    {
        $property = PropertyModel::find($id);
        $propertyOffering = $property->propertyOffering;

        return view('admin.properties.edit_property_offering')->with(['propertyOffering' => $propertyOffering, 'property_id' => $id]);
    }

    public function update_property_offerings(UpdatePropertyOfferingRequest $request, string $id)
    {
        $data = $request->validated();

        $property = PropertyModel::find($id);

        // Retrieve the existing property address
        $propertyOffering = $property->propertyOffering;

        // Check if propertyAddress exists
        if ($propertyOffering) {
            // dd($data);
            // Property address exists, update the existing record
            $propertyOffering->update($data);
        } else {
            // Property address does not exist, create a new record
            $property->propertyOffering()->create($data);
        }
        return redirect(route('admin.manage-property.edit-property-documents', ['id' => $id]))->with('success', 'Offerings updated successfully.');

    }

    public function edit_property_documents(Request $request, string $id)
    {
        $property = PropertyModel::find($id);
        $propertyDocumentModel = $property->propertyDocumentModel;

        return view('admin.properties.edit_property_documents')->with(['propertyDocumentModel' => $propertyDocumentModel, 'property_id' => $id]);
    }

    public function update_property_documents(UpdatePropertyDocumentRequest $request, string $id)
    {
        $data = $request->validated();
        $filteredData = array_filter($data);
        $property = PropertyModel::find($id);

        // Retrieve the existing property address
        $propertyDocumentModel = $property->propertyDocumentModel;
        $newData = [];
        foreach ($filteredData as $key => $value) {

            if ($request->hasFile($key)) {
                $url = $value->store('property_documents', 'public');
                $newData[] = ['document_key' => $key, 'document_value' => $url];
            }

        }
        if (!$propertyDocumentModel->isEmpty()) {
            foreach ($propertyDocumentModel as $model) {
                if (array_key_exists($model->document_key, $filteredData)) {

                    Storage::disk('public')->delete($model->document_value);
                    $model->delete();
                }
            }
            $property->propertyDocumentModel()->createMany($newData);
        } else {
            $property->propertyDocumentModel()->createMany($newData);
        }
        return redirect(route('admin.manage-property.edit-property-metrics', ['id' => $id]))->with('success', 'Documents updated successfully.');

    }

    public function edit_property_metrics(Request $request, string $id)
    {
        $property = PropertyModel::with(['propertyDetails', 'propertyOffering', 'propertyMetrics'])->findOrFail($id);
        
        $propertyMetrics = $property->propertyMetrics;
        if (!$propertyMetrics) {
            $propertyMetrics = PropertyMetricsService::syncForProperty($property);
        }

        return view('admin.properties.edit_property_metrics')->with([
            'property' => $property,
            'propertyMetrics' => $propertyMetrics,
            'property_id' => $id,
        ]);
    }

    public function update_property_metrics(UpdatePropertyMetricsRequest $request, string $id)
    {
        $data = $request->validated();
        $property = PropertyModel::findOrFail($id);

        PropertyMetricsService::syncForProperty($property, $data);

        return redirect(route('manage-property.index'))->with('success', 'Property metrics and investment underwriting saved successfully.');
    }

}
