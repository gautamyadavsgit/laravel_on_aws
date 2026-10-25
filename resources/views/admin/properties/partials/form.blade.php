{{-- Shared create/edit form. $property is a Property (new or existing). --}}
@php
    $isEdit = $property->exists;
    $v = fn (string $field, $default = '') => old($field, $property->{$field} ?? $default);
    $label = 'block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5';
    $availability = $v('availability');
    $availability = $availability instanceof \BackedEnum ? $availability->value : $availability;

    $addressFields = [
        ['address_1', 'Street Address', '255', '123 Ridge Road', 'md:col-span-2'],
        ['address_2', 'Address Line 2 (optional)', '255', 'Suite, unit, etc.', 'md:col-span-2'],
        ['city', 'City', '112', 'Gatlinburg', ''],
        ['state', 'State', '112', 'TN', ''],
        ['zip', 'ZIP / Postal Code', '20', '37738', ''],
    ];
    $detailFields = [
        ['type', 'Property Architecture / Type', 'text', 'e.g. Single Family Luxury Cabin'],
        ['bedrooms', 'Bedrooms', 'number', '4'],
        ['baths', 'Full Bathrooms', 'number', '3'],
        ['half_baths', 'Half Baths', 'number', '1'],
        ['sleeps', 'Sleeps (Guest Capacity)', 'number', '10'],
        ['garages', 'Garage Spaces', 'number', '2'],
        ['square_feets', 'Square Feet', 'number', '3200'],
        ['stories', 'Stories', 'text', '2'],
        ['units', 'Units', 'number', '1'],
        ['lot_size', 'Lot Size (acres)', 'number', '0.75'],
        ['year_built', 'Year Built', 'number', '2018'],
        ['zoning', 'Zoning', 'text', 'Residential'],
        ['value', 'Appraised Value ($)', 'number', '850000'],
    ];
    $offeringFields = [
        ['offering_purchase', 'Purchase Price ($)'],
        ['offering_build_cost', 'Build Cost ($)'],
        ['offering_improvements', 'Improvements ($)'],
        ['offering_closing_cost', 'Closing Costs ($)'],
        ['offering_sourcing_fees', 'Sourcing Fees ($)'],
    ];
@endphp

<form action="{{ $isEdit ? route('admin.properties.update', $property) : route('admin.properties.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
    @csrf
    @if($isEdit) @method('PUT') @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 dark:bg-rose-950/40 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm">
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Primary details -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <h3 class="md:col-span-2 text-sm font-bold text-slate-900 dark:text-white">Primary Listing Details</h3>

        <div>
            <label class="{{ $label }}" for="availability">Availability Status <span class="text-rose-500">*</span></label>
            <select class="select-tw" name="availability" id="availability" required>
                <option value="Available" {{ $availability === 'Available' ? 'selected' : '' }}>Available for Fractional Investment</option>
                <option value="Not Available" {{ $availability === 'Not Available' ? 'selected' : '' }}>Not Available (Sold Out / Closed)</option>
            </select>
        </div>

        <div>
            <label class="{{ $label }}" for="name">Property Name / Title <span class="text-rose-500">*</span></label>
            <input class="input-tw" type="text" name="name" id="name" maxlength="255" value="{{ $v('name') }}" placeholder="e.g. Smoky Mountains Alpine Luxury Lodge" required>
        </div>

        <div class="md:col-span-2">
            <label class="{{ $label }}" for="management_company">Asset Management Company <span class="text-rose-500">*</span></label>
            <input class="input-tw" type="text" name="management_company" id="management_company" maxlength="255" value="{{ $v('management_company') }}" required>
        </div>

        <div class="md:col-span-2">
            <label class="{{ $label }}" for="description">Executive Summary &amp; Description <span class="text-rose-500">*</span></label>
            <textarea class="textarea-tw" name="description" id="description" rows="5" maxlength="20000" required>{{ $v('description') }}</textarea>
        </div>
    </section>

    <!-- Address -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-5 border-t border-slate-200 dark:border-slate-800 pt-6">
        <h3 class="md:col-span-2 text-sm font-bold text-slate-900 dark:text-white">Address</h3>
        @foreach($addressFields as [$name, $text, $max, $placeholder, $span])
            <div class="{{ $span }}">
                <label class="{{ $label }}" for="{{ $name }}">{{ $text }}</label>
                <input class="input-tw" type="text" name="{{ $name }}" id="{{ $name }}" maxlength="{{ $max }}" value="{{ $v($name) }}" placeholder="{{ $placeholder }}" {{ $name === 'address_2' ? '' : 'required' }}>
            </div>
        @endforeach
    </section>

    <!-- Specifications -->
    <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 border-t border-slate-200 dark:border-slate-800 pt-6">
        <h3 class="sm:col-span-2 md:col-span-3 text-sm font-bold text-slate-900 dark:text-white">Specifications &amp; Dimensions</h3>
        @foreach($detailFields as [$name, $text, $type, $placeholder])
            <div>
                <label class="{{ $label }}" for="{{ $name }}">{{ $text }}</label>
                <input class="input-tw" type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" value="{{ $v($name) }}" placeholder="{{ $placeholder }}" required
                    @if($type === 'number') min="{{ $name === 'units' ? 1 : 0 }}" step="{{ $name === 'lot_size' ? '0.01' : 1 }}" @else maxlength="255" @endif>
            </div>
        @endforeach
    </section>

    <!-- Offering -->
    <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 border-t border-slate-200 dark:border-slate-800 pt-6">
        <h3 class="sm:col-span-2 md:col-span-3 text-sm font-bold text-slate-900 dark:text-white">Fractional Offering Breakdown</h3>
        @foreach($offeringFields as [$name, $text])
            <div>
                <label class="{{ $label }}" for="{{ $name }}">{{ $text }}</label>
                <input class="input-tw" type="number" name="{{ $name }}" id="{{ $name }}" min="0" step="1" value="{{ $v($name, 0) }}" required>
            </div>
        @endforeach
    </section>

    <!-- Images -->
    <section class="border-t border-slate-200 dark:border-slate-800 pt-6 space-y-4">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Photos</h3>

        @if($isEdit && $property->images->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                @foreach($property->images as $img)
                    <div class="relative group rounded-lg overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-800 aspect-video flex items-center justify-center">
                        <img src="{{ $img->url }}" alt="Photo" class="w-full h-full object-cover">
                        <button type="submit" form="delete-image-{{ $img->id }}" onclick="return confirm('Remove this photo?')" class="absolute top-1.5 right-1.5 w-7 h-7 rounded-full bg-rose-600/90 text-white flex items-center justify-center text-xs hover:bg-rose-700" title="Remove photo" aria-label="Remove photo">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        @endif

        <div>
            <label class="{{ $label }}" for="property_images">{{ $isEdit ? 'Upload Additional Photos' : 'Upload Photos' }} @unless($isEdit)<span class="text-rose-500">*</span>@endunless</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 dark:border-slate-700 border-dashed rounded-xl hover:border-indigo-500 dark:hover:border-indigo-400 transition bg-slate-50/50 dark:bg-slate-900/50">
                <div class="space-y-1 text-center">
                    <i class="bi bi-cloud-arrow-up text-3xl text-slate-400 dark:text-slate-500 mb-2 block"></i>
                    <div class="flex text-sm text-slate-600 dark:text-slate-400 justify-center">
                        <label for="property_images" class="relative cursor-pointer font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 focus-within:outline-none">
                            <span>Click to select files</span>
                            <input id="property_images" name="property_images[]" type="file" class="sr-only" multiple {{ $isEdit ? '' : 'required' }} accept="image/jpeg,image/png,image/gif,image/webp" data-max-kb="2048" data-allowed-types="image/jpeg,image/png,image/gif,image/webp" data-max-files="20" data-preview-target="#new-image-previews">
                        </label>
                        <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">PNG, JPG, WebP up to 2MB each, max 20</p>
                </div>
            </div>
            <div id="new-image-previews" class="hidden mt-4 grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3" aria-label="Selected photos"></div>
        </div>
    </section>

    <div class="border-t border-slate-200 dark:border-slate-800 pt-5 flex flex-col sm:flex-row items-center justify-between gap-3">
        <a href="{{ route('admin.properties.index') }}" class="btn-secondary-tw w-full sm:w-auto text-center order-2 sm:order-1"><i class="bi bi-x-lg"></i> Cancel</a>
        <button type="submit" class="btn-primary-tw w-full sm:w-auto flex items-center justify-center gap-2 order-1 sm:order-2">
            <i class="bi bi-check-lg text-base"></i> {{ $isEdit ? 'Save Changes' : 'Create Property' }}
        </button>
    </div>
</form>

@if($isEdit)
    @foreach($property->images as $img)
        <form id="delete-image-{{ $img->id }}" action="{{ route('admin.properties.images.destroy', [$property, $img]) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endif
