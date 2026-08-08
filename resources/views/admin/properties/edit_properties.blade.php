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

        <form action="{{ route('manage-property.update', ['manage_property' => $property_id]) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Grid: Basic Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="availability">
                        Availability Status <span class="text-rose-500">*</span>
                    </label>
                    <select class="select-tw" name="availability" id="availability" required>
                        <option value="Available" {{ ($property->availability ?? '') == 'Available' ? 'selected' : '' }}>Available for Fractional Investment</option>
                        <option value="Not Available" {{ ($property->availability ?? '') == 'Not Available' ? 'selected' : '' }}>Not Available (Sold Out / Closed)</option>
                    </select>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Controls visibility and active investment status in the investor portal.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="name">
                        Property Name / Title <span class="text-rose-500">*</span>
                    </label>
                    <input class="input-tw" type="text" name="name" id="name" value="{{ $property->name ?? old('name') }}" placeholder="e.g. Smoky Mountains Alpine Luxury Lodge" required>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Full branded headline used throughout public listings and deed certificates.</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="management_company">
                        Asset Management Company <span class="text-rose-500">*</span>
                    </label>
                    <input class="input-tw" type="text" name="management_company" id="management_company" value="{{ $property->management_company ?? old('management_company') }}" placeholder="e.g. Robert Premier Hospitality Management LLC" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="description">
                        Executive Summary & Description <span class="text-rose-500">*</span>
                    </label>
                    <textarea class="textarea-tw" name="description" id="description" rows="5" required placeholder="Provide an in-depth summary of the real estate asset, vacation rental appeal, and architectural features...">{{ $property->description ?? old('description') }}</textarea>
                </div>
            </div>

            <!-- Existing Image Gallery Preview -->
            @if(isset($property->propertyImage) && $property->propertyImage->count() > 0)
                <div class="border-t border-slate-200 dark:border-slate-800 pt-5">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">
                            Current Gallery Photos ({{ $property->propertyImage->count() }} Images)
                        </label>
                        <span class="text-xs text-slate-500 dark:text-slate-400">Stored on public disk</span>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                        @foreach($property->propertyImage as $img)
                            <div class="relative group rounded-lg overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-800 aspect-video flex items-center justify-center">
                                <img src="{{ asset('storage/' . $img->property_image_value) }}" alt="Photo" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' fill=\'%2364748b\'><rect width=\'100\' height=\'100\' fill=\'%23f1f5f9\'/><text x=\'50%\' y=\'50%\' font-size=\'10\' text-anchor=\'middle\' dy=\'.3em\'>Gallery Photo</text></svg>'">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- File Upload Zone for Adding More Images -->
            <div class="border-t border-slate-200 dark:border-slate-800 pt-5">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="property_images">
                    Upload Additional Photos
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 dark:border-slate-700 border-dashed rounded-xl hover:border-indigo-500 dark:hover:border-indigo-400 transition bg-slate-50/50 dark:bg-slate-900/50">
                    <div class="space-y-1 text-center">
                        <i class="bi bi-cloud-arrow-up text-3xl text-slate-400 dark:text-slate-500 mb-2 block"></i>
                        <div class="flex text-sm text-slate-600 dark:text-slate-400 justify-center">
                            <label for="property_images" class="relative cursor-pointer font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 focus-within:outline-none">
                                <span>Click to select files</span>
                                <input id="property_images" name="property_images[]" type="file" class="sr-only" multiple accept="image/*">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">PNG, JPG, WebP up to 10MB each</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="border-t border-slate-200 dark:border-slate-800 pt-5 flex flex-col sm:flex-row items-center justify-between gap-3">
                <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw w-full sm:w-auto text-center order-2 sm:order-1">
                    <i class="bi bi-x-lg"></i> Cancel
                </a>
                <div class="flex items-center gap-2.5 w-full sm:w-auto order-1 sm:order-2">
                    <button type="submit" class="btn-primary-tw w-full sm:w-auto flex items-center justify-center gap-2">
                        <i class="bi bi-check-lg text-base"></i> Save & Continue to Address
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
