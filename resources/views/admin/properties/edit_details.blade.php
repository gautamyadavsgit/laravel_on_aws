@extends('admin.common.page')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
            <i class="bi bi-info-circle text-indigo-600 dark:text-indigo-400"></i> Edit Property Specifications
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Configure dimensions, structural specifications, and valuation for property #{{ $property_id }}.</p>
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
                <i class="bi bi-sliders text-indigo-600 dark:text-indigo-400"></i> Specifications & Dimensions
            </h2>
            <span class="badge-tw badge-info-tw">Property ID #{{ $property_id }}</span>
        </div>

        <form action="{{ route('admin.manage-property.update-details', ['id' => $property_id]) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="type">Property Type</label>
                    <input class="input-tw" type="text" name="type" id="type" value="{{ $propertyDetails['type'] ?? old('type') }}" placeholder="e.g. Single Family">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="bedrooms">Bedrooms</label>
                    <input class="input-tw" type="number" name="bedrooms" id="bedrooms" value="{{ $propertyDetails['bedrooms'] ?? old('bedrooms') }}" placeholder="4">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="baths">Full Bathrooms</label>
                    <input class="input-tw" type="number" name="baths" id="baths" value="{{ $propertyDetails['baths'] ?? old('baths') }}" placeholder="3">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="half_baths">Half Baths</label>
                    <input class="input-tw" type="number" name="half_baths" id="half_baths" value="{{ $propertyDetails['half_baths'] ?? old('half_baths') }}" placeholder="1">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="sleeps">Sleeps (Capacity)</label>
                    <input class="input-tw" type="number" name="sleeps" id="sleeps" value="{{ $propertyDetails['sleeps'] ?? old('sleeps') }}" placeholder="10">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="garages">Garages</label>
                    <input class="input-tw" type="number" name="garages" id="garages" value="{{ $propertyDetails['garages'] ?? old('garages') }}" placeholder="2">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="square_feets">Square Feet (SqFt)</label>
                    <input class="input-tw" type="number" name="square_feets" id="square_feets" value="{{ $propertyDetails['square_feets'] ?? old('square_feets') }}" placeholder="2850">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="units">Total Units</label>
                    <input class="input-tw" type="number" name="units" id="units" value="{{ $propertyDetails['units'] ?? old('units') }}" placeholder="1">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="lot_size">Lot Size (SqFt / Acres)</label>
                    <input class="input-tw" type="number" name="lot_size" id="lot_size" value="{{ $propertyDetails['lot_size'] ?? old('lot_size') }}" placeholder="12000">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="year_built">Year Built</label>
                    <input class="input-tw" type="number" name="year_built" id="year_built" value="{{ $propertyDetails['year_built'] ?? old('year_built') }}" placeholder="2021">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="zoning">Zoning Code</label>
                    <input class="input-tw" type="text" name="zoning" id="zoning" value="{{ $propertyDetails['zoning'] ?? old('zoning') }}" placeholder="R-1">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="value">Estimated Valuation ($)</label>
                    <input class="input-tw" type="number" name="value" id="value" value="{{ $propertyDetails['value'] ?? old('value') }}" placeholder="750000">
                </div>

                <div class="sm:col-span-2 md:col-span-3">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="stories">Stories / Structural Notes</label>
                    <textarea class="textarea-tw" name="stories" id="stories" rows="2" placeholder="e.g. 2-story contemporary cabin with walkout basement">{{ $propertyDetails['stories'] ?? old('stories') }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="btn-primary-tw">
                    <i class="bi bi-check-lg"></i> Update Specifications
                </button>
                <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
