@extends('admin.common.page')

@section('content')
<div class="max-w-5xl mx-auto w-full">
    <!-- Property Stepper Header -->
    <div class="mb-6 space-y-4">
        <!-- Top Meta Card -->
        <div class="card-tw p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 border-l-4 border-l-indigo-600 dark:border-l-indigo-500">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-xl bg-indigo-600/10 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl shrink-0 font-bold">
                    <i class="bi bi-plus-circle"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                            Create New Property
                        </h1>
                        <span class="badge-tw badge-info-tw">Step 1 of 8</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Enter core property information to initialize a new fractional real estate investment offering.
                    </p>
                </div>
            </div>

            <!-- Quick Actions -->
            <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw text-xs py-2 px-3 flex items-center gap-1.5">
                <i class="bi bi-arrow-left"></i>
                <span>Back to Properties</span>
            </a>
        </div>

        <!-- Stepper Tabs -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-1.5 shadow-sm overflow-x-auto">
            <nav class="flex items-center gap-1 min-w-max" aria-label="Property Creation Stages">
                <span class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold bg-indigo-600 text-white shadow-sm">
                    <i class="bi bi-building"></i>
                    <span>1. Primary</span>
                </span>
                <i class="bi bi-chevron-right text-slate-300 dark:text-slate-700 text-xs"></i>
                <span class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold text-slate-400 dark:text-slate-600">
                    <i class="bi bi-geo-alt"></i>
                    <span>2. Address</span>
                </span>
                <i class="bi bi-chevron-right text-slate-300 dark:text-slate-700 text-xs"></i>
                <span class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold text-slate-400 dark:text-slate-600">
                    <i class="bi bi-sliders"></i>
                    <span>3. Specs</span>
                </span>
                <i class="bi bi-chevron-right text-slate-300 dark:text-slate-700 text-xs"></i>
                <span class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold text-slate-400 dark:text-slate-600">
                    <i class="bi bi-stars"></i>
                    <span>4. Amenities</span>
                </span>
                <i class="bi bi-chevron-right text-slate-300 dark:text-slate-700 text-xs"></i>
                <span class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold text-slate-400 dark:text-slate-600">
                    <i class="bi bi-bounding-box"></i>
                    <span>5. Floorplan</span>
                </span>
                <i class="bi bi-chevron-right text-slate-300 dark:text-slate-700 text-xs"></i>
                <span class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold text-slate-400 dark:text-slate-600">
                    <i class="bi bi-tags"></i>
                    <span>6. Offerings</span>
                </span>
                <i class="bi bi-chevron-right text-slate-300 dark:text-slate-700 text-xs"></i>
                <span class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold text-slate-400 dark:text-slate-600">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>7. Documents</span>
                </span>
                <i class="bi bi-chevron-right text-slate-300 dark:text-slate-700 text-xs"></i>
                <span class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold text-slate-400 dark:text-slate-600">
                    <i class="bi bi-graph-up"></i>
                    <span>8. Financials</span>
                </span>
            </nav>
        </div>
    </div>

    <div class="card-tw shadow-sm">
        <div class="card-header-tw flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-indigo-600/10 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold">
                    <i class="bi bi-building"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">
                        Stage 1: Primary Listing Details
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Core listing attributes, availability status, and asset overview.</p>
                </div>
            </div>
            <span class="badge-tw badge-info-tw hidden sm:inline-flex">Step 1 of 8</span>
        </div>

        <form action="{{ route('manage-property.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="availability">
                        Availability Status <span class="text-rose-500">*</span>
                    </label>
                    <select class="select-tw" name="availability" id="availability" required>
                        <option value="" disabled {{ old('availability') ? '' : 'selected' }}>Select Availability</option>
                        <option value="Available" {{ old('availability') === 'Available' ? 'selected' : '' }}>Available for Fractional Investment</option>
                        <option value="Not Available" {{ old('availability') === 'Not Available' ? 'selected' : '' }}>Not Available (Sold Out / Closed)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="name">
                        Property Name <span class="text-rose-500">*</span>
                    </label>
                    <input class="input-tw" type="text" name="name" maxlength="255" id="name" value="{{ old('name') }}" placeholder="e.g. Smoky Mountain Luxury Estate" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="management_company">
                        Management Company <span class="text-rose-500">*</span>
                    </label>
                    <input class="input-tw" type="text" name="management_company" maxlength="255" id="management_company" value="{{ old('management_company') }}" placeholder="e.g. Gautam REI Asset Management" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="property_images">
                        Property Images (Multiple) <span class="text-rose-500">*</span>
                    </label>
                    <input class="input-tw file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" type="file" name="property_images[]" id="property_images" accept="image/jpeg,image/png,image/gif,image/webp" data-max-kb="2048" data-allowed-types="image/jpeg,image/png,image/gif,image/webp" data-max-files="20" multiple required>
                    <span class="text-xs text-slate-400 dark:text-slate-500 mt-1 block">Upload high-res JPG, PNG, or WEBP photos.</span>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="description">
                        Detailed Property Description <span class="text-rose-500">*</span>
                    </label>
                    <textarea class="textarea-tw" name="description" maxlength="20000" id="description" rows="4" placeholder="Provide an extensive summary of the real estate opportunity..." required>{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="border-t border-slate-200 dark:border-slate-800 pt-5 flex items-center gap-3">
                <button type="submit" class="btn-primary-tw">
                    <i class="bi bi-check-lg text-base"></i> Create & Proceed to Address <i class="bi bi-arrow-right"></i>
                </button>
                <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
