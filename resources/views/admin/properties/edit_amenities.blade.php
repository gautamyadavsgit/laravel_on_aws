@extends('admin.common.page')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
            <i class="bi bi-stars text-indigo-600 dark:text-indigo-400"></i> Edit Property Amenities
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage feature and amenity tags for property #{{ $property_id }}.</p>
    </div>
    <div>
        <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw">
            <i class="bi bi-arrow-left"></i> Back to Properties
        </a>
    </div>
</div>

<div class="max-w-4xl mx-auto w-full">
    <div class="card-tw">
        <div class="card-header-tw">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-list-check text-indigo-600 dark:text-indigo-400"></i> Amenities List
            </h2>
            <span class="badge-tw badge-info-tw">Property ID #{{ $property_id }}</span>
        </div>

        <form action="{{ route('admin.manage-property.update-aminities', ['id' => $property_id]) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="property_amenities">
                    Amenities (Comma Separated) <span class="text-rose-500">*</span>
                </label>
                <textarea class="textarea-tw" required name="property_amenities" id="property_amenities" rows="4" placeholder="e.g. Mountain View, Hot Tub, High-Speed WiFi, Outdoor Firepit, EV Charger">{{ $propertyAmenities[0]->property_amenities ?? old('property_amenities') }}</textarea>
                <span class="text-xs text-slate-400 dark:text-slate-500 mt-1 block">Separate each amenity with a comma (,) e.g. Hot Tub, High Speed WiFi, Fireplace.</span>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="btn-primary-tw">
                    <i class="bi bi-check-lg"></i> Update Amenities
                </button>
                <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
