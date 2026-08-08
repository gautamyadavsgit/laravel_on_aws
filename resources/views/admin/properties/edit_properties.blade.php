@extends('admin.common.page')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="bi bi-pencil-square text-primary"></i> Edit Property Details
        </h1>
        <p class="page-subtitle">Update primary details for property #{{ $property_id }} ({{ $property->name ?? 'Untitled' }}).</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('manage-property.index') }}" class="btn btn-secondary-custom">
            <i class="bi bi-arrow-left"></i> Back to Properties
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="form-card">
            <div class="form-card-header">
                <h2 class="form-card-title">
                    <i class="bi bi-building text-primary"></i> Primary Property Information
                </h2>
                <span class="form-card-badge">Property ID #{{ $property_id }}</span>
            </div>
            <div class="form-card-body">
                <form class="needs-validation" action="{{ route('manage-property.update', ['manage_property' => $property_id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="custom-form-group">
                                <label class="form-label" for="availability">Availability Status</label>
                                <select class="form-select" name="availability" id="availability" required>
                                    <option value="Available" {{ ($property->availability ?? '') == 'Available' ? 'selected' : '' }}>Available</option>
                                    <option value="Not Available" {{ ($property->availability ?? '') == 'Not Available' ? 'selected' : '' }}>Not Available</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="custom-form-group">
                                <label class="form-label" for="name">Property Name</label>
                                <input class="form-control" type="text" name="name" id="name" value="{{ $property->name ?? old('name') }}" required>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="custom-form-group">
                                <label class="form-label" for="management_company">Management Company</label>
                                <input class="form-control" type="text" name="management_company" id="management_company" value="{{ $property->management_company ?? old('management_company') }}" required>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="custom-form-group">
                                <label class="form-label" for="property_images">Add Additional Property Images</label>
                                <input class="form-control" type="file" name="property_images[]" id="property_images" accept="image/*" multiple>
                                <small class="text-muted">Upload new photos to add to this property's gallery.</small>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="custom-form-group">
                                <label class="form-label" for="description">Detailed Description</label>
                                <textarea class="form-control" name="description" id="description" rows="4" required>{{ $property->description ?? old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="bi bi-check-lg"></i> Update Property Details
                        </button>
                        <a href="{{ route('manage-property.index') }}" class="btn btn-secondary-custom">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
