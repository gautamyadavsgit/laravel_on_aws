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
            <span class="badge-tw badge-info-tw hidden sm:inline-flex">Step 2 of 5</span>
        </div>

        <form action="{{ route('admin.properties.address.update', $property) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="address_1">
                        Address Line 1 <span class="text-rose-500">*</span>
                    </label>
                    <textarea class="textarea-tw" name="address_1" maxlength="255" id="address_1" rows="2" required placeholder="Street address, P.O. box">{{ old('address_1', $property->address['address_1'] ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="address_2">
                        Address Line 2 (Optional)
                    </label>
                    <textarea class="textarea-tw" name="address_2" maxlength="255" id="address_2" rows="2" placeholder="Apartment, suite, unit, building, floor, etc.">{{ old('address_2', $property->address['address_2'] ?? '') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="city">
                            City <span class="text-rose-500">*</span>
                        </label>
                        <input class="input-tw" type="text" name="city" maxlength="112" id="city" value="{{ old('city', $property->address['city'] ?? '') }}" placeholder="City name" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="state">
                            State / Province <span class="text-rose-500">*</span>
                        </label>
                        <input class="input-tw" type="text" name="state" maxlength="112" id="state" value="{{ old('state', $property->address['state'] ?? '') }}" placeholder="e.g. TN, CO, FL" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="zip">
                            Postal / Zip Code <span class="text-rose-500">*</span>
                        </label>
                        <input class="input-tw" type="text" name="zip" maxlength="20" pattern="[A-Za-z0-9][A-Za-z0-9 \-]*" title="Letters, numbers, spaces and hyphens only" id="zip" value="{{ old('zip', $property->address['zip'] ?? '') }}" placeholder="e.g. 37738" required>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-200 dark:border-slate-800 pt-5 flex flex-col sm:flex-row items-center justify-between gap-3">
                <a href="{{ route('admin.properties.edit', $property) }}" class="btn-secondary-tw w-full sm:w-auto text-center order-2 sm:order-1">
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
