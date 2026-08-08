@extends('admin.common.page')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
            <i class="bi bi-link-45deg text-indigo-600 dark:text-indigo-400"></i> Edit Property External URLs
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Configure public OTA listing URLs, maps, and valuation links for property #{{ $property_id }}.</p>
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
                <i class="bi bi-globe text-indigo-600 dark:text-indigo-400"></i> External Listing Links
            </h2>
            <span class="badge-tw badge-info-tw">Property ID #{{ $property_id }}</span>
        </div>

        <form action="{{ route('admin.manage-property.update-property-urls', ['id' => $property_id]) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="google_map">
                        Google Maps Location URL <span class="text-rose-500">*</span>
                    </label>
                    <input class="input-tw" value="{{ $propertyUrls->google_map ?? old('google_map') }}" type="url" required name="google_map" id="google_map" placeholder="https://maps.google.com/...">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="zillow">
                        Zillow Valuation URL <span class="text-rose-500">*</span>
                    </label>
                    <input class="input-tw" value="{{ $propertyUrls->zillow ?? old('zillow') }}" type="url" required name="zillow" id="zillow" placeholder="https://www.zillow.com/homedetails/...">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="airbnb">
                        Airbnb Listing URL <span class="text-rose-500">*</span>
                    </label>
                    <input class="input-tw" value="{{ $propertyUrls->airbnb ?? old('airbnb') }}" type="url" required name="airbnb" id="airbnb" placeholder="https://www.airbnb.com/rooms/...">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="vrbo">
                        VRBO Listing URL <span class="text-rose-500">*</span>
                    </label>
                    <input class="input-tw" value="{{ $propertyUrls->vrbo ?? old('vrbo') }}" type="url" required name="vrbo" id="vrbo" placeholder="https://www.vrbo.com/...">
                </div>

                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="alt_listing_1">Alternative Listing 1</label>
                        <input class="input-tw" value="{{ $propertyUrls->alt_listing_1 ?? old('alt_listing_1') }}" type="url" required name="alt_listing_1" id="alt_listing_1" placeholder="https://...">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="alt_listing_2">Alternative Listing 2</label>
                        <input class="input-tw" value="{{ $propertyUrls->alt_listing_2 ?? old('alt_listing_2') }}" type="url" required name="alt_listing_2" id="alt_listing_2" placeholder="https://...">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="alt_listing_3">Alternative Listing 3</label>
                        <input class="input-tw" value="{{ $propertyUrls->alt_listing_3 ?? old('alt_listing_3') }}" type="url" required name="alt_listing_3" id="alt_listing_3" placeholder="https://...">
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="btn-primary-tw">
                    <i class="bi bi-check-lg"></i> Update Property URLs
                </button>
                <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
