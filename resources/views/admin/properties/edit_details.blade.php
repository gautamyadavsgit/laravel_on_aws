@extends('admin.common.page')

@section('content')
<div class="max-w-5xl mx-auto w-full">
    <!-- Shared Stepper Header -->
    @include('admin.properties.partials.nav')

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 dark:bg-emerald-950/40 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2.5">
                <i class="bi bi-check-circle-fill text-emerald-600 dark:text-emerald-400 text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-200">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    <div class="card-tw shadow-sm">
        <div class="card-header-tw flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-indigo-600/10 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold">
                    <i class="bi bi-sliders"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">
                        Stage 3: Property Specifications & Dimensions
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Structural capacity, square footage, bed/bath count, and appraisal value.</p>
                </div>
            </div>
            <span class="badge-tw badge-info-tw hidden sm:inline-flex">Step 3 of 8</span>
        </div>

        <form action="{{ route('admin.manage-property.update-details', ['id' => $property_id]) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="type">
                        Property Architecture / Type
                    </label>
                    <input class="input-tw" type="text" name="type" id="type" value="{{ $propertyDetails['type'] ?? old('type') }}" placeholder="e.g. Single Family Luxury Cabin">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="bedrooms">
                        Bedrooms
                    </label>
                    <input class="input-tw" type="number" name="bedrooms" id="bedrooms" value="{{ $propertyDetails['bedrooms'] ?? old('bedrooms') }}" placeholder="4">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="baths">
                        Full Bathrooms
                    </label>
                    <input class="input-tw" type="number" name="baths" id="baths" value="{{ $propertyDetails['baths'] ?? old('baths') }}" placeholder="3">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="half_baths">
                        Half Baths
                    </label>
                    <input class="input-tw" type="number" name="half_baths" id="half_baths" value="{{ $propertyDetails['half_baths'] ?? old('half_baths') }}" placeholder="1">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="sleeps">
                        Sleeps (Guest Capacity)
                    </label>
                    <input class="input-tw" type="number" name="sleeps" id="sleeps" value="{{ $propertyDetails['sleeps'] ?? old('sleeps') }}" placeholder="10">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="garages">
                        Garage Spaces
                    </label>
                    <input class="input-tw" type="number" name="garages" id="garages" value="{{ $propertyDetails['garages'] ?? old('garages') }}" placeholder="2">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="square_feets">
                        Living Area (SqFt)
                    </label>
                    <input class="input-tw" type="number" name="square_feets" id="square_feets" value="{{ $propertyDetails['square_feets'] ?? old('square_feets') }}" placeholder="3200">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="units">
                        Total Units
                    </label>
                    <input class="input-tw" type="number" name="units" id="units" value="{{ $propertyDetails['units'] ?? old('units') }}" placeholder="1">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="lot_size">
                        Lot Size (SqFt)
                    </label>
                    <input class="input-tw" type="number" name="lot_size" id="lot_size" value="{{ $propertyDetails['lot_size'] ?? old('lot_size') }}" placeholder="15000">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="year_built">
                        Year Built
                    </label>
                    <input class="input-tw" type="number" name="year_built" id="year_built" value="{{ $propertyDetails['year_built'] ?? old('year_built') }}" placeholder="2022">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="zoning">
                        Zoning Code
                    </label>
                    <input class="input-tw" type="text" name="zoning" id="zoning" value="{{ $propertyDetails['zoning'] ?? old('zoning') }}" placeholder="R-1">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="value">
                        Appraised Value ($)
                    </label>
                    <input class="input-tw" type="number" name="value" id="value" value="{{ $propertyDetails['value'] ?? old('value') }}" placeholder="850000">
                </div>

                <div class="sm:col-span-2 md:col-span-3">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="stories">
                        Stories & Structural Architecture Notes
                    </label>
                    <textarea class="textarea-tw" name="stories" id="stories" rows="2" placeholder="e.g. 2-story custom timber construction with panoramic deck and stone fireplace">{{ $propertyDetails['stories'] ?? old('stories') }}</textarea>
                </div>
            </div>

            <div class="border-t border-slate-200 dark:border-slate-800 pt-5 flex flex-col sm:flex-row items-center justify-between gap-3">
                <a href="{{ route('admin.manage-property.edit-address', ['id' => $property_id]) }}" class="btn-secondary-tw w-full sm:w-auto text-center order-2 sm:order-1">
                    <i class="bi bi-arrow-left"></i> Previous Step
                </a>
                <div class="flex items-center gap-2.5 w-full sm:w-auto order-1 sm:order-2">
                    <button type="submit" class="btn-primary-tw w-full sm:w-auto flex items-center justify-center gap-2">
                        <i class="bi bi-check-lg text-base"></i> Save & Continue to Amenities
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
