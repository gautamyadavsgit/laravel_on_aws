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
                    <i class="bi bi-stars"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">
                        Stage 4: Property Amenities & Highlights
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Luxury amenities, guest perks, entertainment facilities, and smart home features.</p>
                </div>
            </div>
            <span class="badge-tw badge-info-tw hidden sm:inline-flex">Step 4 of 8</span>
        </div>

        <form action="{{ route('admin.manage-property.update-aminities', ['id' => $property_id]) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="property_amenities">
                    Amenities List (Comma Separated) <span class="text-rose-500">*</span>
                </label>
                <textarea class="textarea-tw" required name="property_amenities" id="property_amenities" rows="5" placeholder="e.g. Heated Infinity Pool, Private Mountain View Deck, Cedar Hot Tub, Outdoor Stone Firepit, High-Speed Starlink WiFi, Tesla Level 2 EV Charger, Game Room with Billiards, Gourmet Chef Kitchen, Radiant Floor Heating">{{ isset($propertyAmenities) && count($propertyAmenities) > 0 ? implode(', ', $propertyAmenities->pluck('property_amenities')->toArray()) : ($propertyAmenities[0]->property_amenities ?? old('property_amenities')) }}</textarea>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                    <i class="bi bi-info-circle text-indigo-500"></i> Enter tags separated by commas. Each item will automatically be formatted as a styled badge on the investor portal.
                </p>
            </div>

            <!-- Quick Suggestions Pills -->
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
                <span class="text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider block mb-2">Common Luxury Vacation Amenities:</span>
                <div class="flex flex-wrap gap-1.5">
                    @foreach(['Mountain Panorama', 'Cedar Hot Tub', 'High-Speed WiFi', 'EV Charging Station', 'Gourmet Kitchen', 'Outdoor Firepit', 'Private Spa', 'Billiards / Arcade', 'Smart Home Audio', 'Keyless Entry'] as $tag)
                        <button type="button" onclick="appendAmenity('{{ $tag }}')" class="px-2.5 py-1 rounded-lg text-xs font-medium bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                            + {{ $tag }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-slate-200 dark:border-slate-800 pt-5 flex flex-col sm:flex-row items-center justify-between gap-3">
                <a href="{{ route('admin.manage-property.edit-details', ['id' => $property_id]) }}" class="btn-secondary-tw w-full sm:w-auto text-center order-2 sm:order-1">
                    <i class="bi bi-arrow-left"></i> Previous Step
                </a>
                <div class="flex items-center gap-2.5 w-full sm:w-auto order-1 sm:order-2">
                    <button type="submit" class="btn-primary-tw w-full sm:w-auto flex items-center justify-center gap-2">
                        <i class="bi bi-check-lg text-base"></i> Save & Continue to Floorplans
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function appendAmenity(name) {
        const textarea = document.getElementById('property_amenities');
        let current = textarea.value.trim();
        if (current.length === 0) {
            textarea.value = name;
        } else if (!current.includes(name)) {
            textarea.value = current.replace(/,\s*$/, '') + ', ' + name;
        }
        textarea.focus();
    }
</script>
@endsection
