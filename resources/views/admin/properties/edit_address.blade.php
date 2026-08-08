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
                    <i class="bi bi-geo-alt"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">
                        Stage 2: Property Address & Location
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Postal street address, city, state jurisdiction, and postal code.</p>
                </div>
            </div>
            <span class="badge-tw badge-info-tw hidden sm:inline-flex">Step 2 of 8</span>
        </div>

        <form action="{{ route('admin.manage-property.update-address', ['id' => $property_id]) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="address_1">
                        Address Line 1 <span class="text-rose-500">*</span>
                    </label>
                    <textarea class="textarea-tw" name="address_1" id="address_1" rows="2" required placeholder="Street address, P.O. box">{{ $propertyAddress['address_1'] ?? old('address_1') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="address_2">
                        Address Line 2 (Optional)
                    </label>
                    <textarea class="textarea-tw" name="address_2" id="address_2" rows="2" placeholder="Apartment, suite, unit, building, floor, etc.">{{ $propertyAddress['address_2'] ?? old('address_2') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="city">
                            City <span class="text-rose-500">*</span>
                        </label>
                        <input class="input-tw" type="text" name="city" id="city" value="{{ $propertyAddress['city'] ?? old('city') }}" placeholder="City name" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="state">
                            State / Province <span class="text-rose-500">*</span>
                        </label>
                        <input class="input-tw" type="text" name="state" id="state" value="{{ $propertyAddress['state'] ?? old('state') }}" placeholder="e.g. TN, CO, FL" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="zip">
                            Postal / Zip Code <span class="text-rose-500">*</span>
                        </label>
                        <input class="input-tw" type="text" name="zip" id="zip" value="{{ $propertyAddress['zip'] ?? old('zip') }}" placeholder="e.g. 37738" required>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-200 dark:border-slate-800 pt-5 flex flex-col sm:flex-row items-center justify-between gap-3">
                <a href="{{ route('manage-property.edit', ['manage_property' => $property_id]) }}" class="btn-secondary-tw w-full sm:w-auto text-center order-2 sm:order-1">
                    <i class="bi bi-arrow-left"></i> Previous Step
                </a>
                <div class="flex items-center gap-2.5 w-full sm:w-auto order-1 sm:order-2">
                    <button type="submit" class="btn-primary-tw w-full sm:w-auto flex items-center justify-center gap-2">
                        <i class="bi bi-check-lg text-base"></i> Save & Continue to Specs
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
