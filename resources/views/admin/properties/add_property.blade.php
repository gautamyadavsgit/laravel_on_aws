@extends('admin.common.page')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
            <i class="bi bi-plus-circle text-indigo-600 dark:text-indigo-400"></i> Create New Property
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Enter core property information to initialize a new fractional real estate investment offering.</p>
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
                <i class="bi bi-building text-indigo-600 dark:text-indigo-400"></i> Primary Property Information
            </h2>
            <span class="badge-tw badge-info-tw">Step 1 of Setup</span>
        </div>

        <form action="{{ route('manage-property.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="availability">
                        Availability Status <span class="text-rose-500">*</span>
                    </label>
                    <select class="select-tw" name="availability" id="availability" required>
                        <option value="" disabled {{ old('availability') ? '' : 'selected' }}>Select Availability</option>
                        <option value="Available" {{ old('availability') === 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="Not Available" {{ old('availability') === 'Not Available' ? 'selected' : '' }}>Not Available</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="name">
                        Property Name <span class="text-rose-500">*</span>
                    </label>
                    <input class="input-tw" type="text" name="name" id="name" value="{{ old('name') }}" placeholder="e.g. Smoky Mountain Luxury Estate" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="management_company">
                        Management Company <span class="text-rose-500">*</span>
                    </label>
                    <input class="input-tw" type="text" name="management_company" id="management_company" value="{{ old('management_company') }}" placeholder="e.g. Gautam REI Asset Management" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="property_images">
                        Property Images (Multiple) <span class="text-rose-500">*</span>
                    </label>
                    <input class="input-tw file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" type="file" name="property_images[]" id="property_images" accept="image/*" multiple required>
                    <span class="text-xs text-slate-400 dark:text-slate-500 mt-1 block">Upload high-res JPG, PNG, or WEBP photos.</span>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="description">
                        Detailed Property Description <span class="text-rose-500">*</span>
                    </label>
                    <textarea class="textarea-tw" name="description" id="description" rows="4" placeholder="Provide an extensive summary of the real estate opportunity..." required>{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="btn-primary-tw">
                    <i class="bi bi-check-lg"></i> Create & Proceed to Address
                </button>
                <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
