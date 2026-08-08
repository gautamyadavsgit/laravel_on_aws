@extends('admin.common.page')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
            <i class="bi bi-geo-alt text-indigo-600 dark:text-indigo-400"></i> Edit Property Address
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Configure geographical location and postal address for property #{{ $property_id }}.</p>
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
                <i class="bi bi-pin-map text-indigo-600 dark:text-indigo-400"></i> Address Details
            </h2>
            <span class="badge-tw badge-info-tw">Property ID #{{ $property_id }}</span>
        </div>

        <form action="{{ route('admin.manage-property.update-address', ['id' => $property_id]) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="address_1">
                    Address Line 1 <span class="text-rose-500">*</span>
                </label>
                <textarea class="textarea-tw" name="address_1" id="address_1" rows="2" required placeholder="Street address, P.O. box">{{ $propertyAddress['address_1'] ?? old('address_1') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="address_2">
                    Address Line 2
                </label>
                <textarea class="textarea-tw" name="address_2" id="address_2" rows="2" placeholder="Apartment, suite, unit, building, floor, etc.">{{ $propertyAddress['address_2'] ?? old('address_2') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="city">City</label>
                    <input class="input-tw" type="text" name="city" id="city" value="{{ $propertyAddress['city'] ?? old('city') }}" placeholder="City name">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="state">State</label>
                    <input class="input-tw" type="text" name="state" id="state" value="{{ $propertyAddress['state'] ?? old('state') }}" placeholder="State / Province">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="zip">Postal / Zip</label>
                    <input class="input-tw" type="text" name="zip" id="zip" value="{{ $propertyAddress['zip'] ?? old('zip') }}" placeholder="Zip code">
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="btn-primary-tw">
                    <i class="bi bi-check-lg"></i> Update Address
                </button>
                <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
