@php
    $currentRoute = Route::currentRouteName();
    $propId = $property_id ?? $property->id ?? 1;
    $propModel = isset($property) && is_object($property) ? $property : \App\Models\PropertyModel::find($propId);
@endphp

<!-- Property Stepper Header -->
<div class="mb-6 space-y-4">
    <!-- Top Meta Card -->
    <div class="card-tw p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 border-l-4 border-l-indigo-600 dark:border-l-indigo-500">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-xl bg-indigo-600/10 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl shrink-0 font-bold">
                <i class="bi bi-building"></i>
            </div>
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                        {{ $propModel->name ?? 'Edit Property' }}
                    </h1>
                    <span class="badge-tw badge-info-tw">ID #{{ $propId }}</span>
                    @if(isset($propModel->availability))
                        @if($propModel->availability === 'Available')
                            <span class="badge-tw badge-success-tw flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Available
                            </span>
                        @else
                            <span class="badge-tw badge-warning-tw flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> {{ $propModel->availability }}
                            </span>
                        @endif
                    @endif
                </div>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    {{ $propModel->management_company ?? 'Gautam Real Estate Asset' }} &bull; Last updated {{ isset($propModel->updated_at) ? $propModel->updated_at->diffForHumans() : 'recently' }}
                </p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="flex items-center gap-2 shrink-0 flex-wrap">
            <a href="{{ url('/property_singlepage?id=' . $propId) }}" target="_blank" class="btn-secondary-tw text-xs py-2 px-3 flex items-center gap-1.5" title="Preview public listing">
                <i class="bi bi-box-arrow-up-right"></i>
                <span class="hidden sm:inline">Preview Live</span>
            </a>
            <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw text-xs py-2 px-3 flex items-center gap-1.5">
                <i class="bi bi-arrow-left"></i>
                <span>All Properties</span>
            </a>
        </div>
    </div>

    <!-- Stepper Tabs -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-1.5 shadow-sm overflow-x-auto">
        <nav class="flex items-center gap-1 min-w-max" aria-label="Property Edit Stages">
            <!-- 1. Primary -->
            <a href="{{ route('manage-property.edit', ['manage_property' => $propId]) }}" 
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold transition {{ $currentRoute === 'manage-property.edit' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <i class="bi bi-building"></i>
                <span>1. Primary</span>
            </a>

            <i class="bi bi-chevron-right text-slate-300 dark:text-slate-700 text-xs"></i>

            <!-- 2. Address -->
            <a href="{{ route('admin.manage-property.edit-address', ['id' => $propId]) }}" 
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold transition {{ $currentRoute === 'admin.manage-property.edit-address' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <i class="bi bi-geo-alt"></i>
                <span>2. Address</span>
            </a>

            <i class="bi bi-chevron-right text-slate-300 dark:text-slate-700 text-xs"></i>

            <!-- 3. Specs -->
            <a href="{{ route('admin.manage-property.edit-details', ['id' => $propId]) }}" 
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold transition {{ $currentRoute === 'admin.manage-property.edit-details' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <i class="bi bi-sliders"></i>
                <span>3. Specs</span>
            </a>

            <i class="bi bi-chevron-right text-slate-300 dark:text-slate-700 text-xs"></i>

            <!-- 4. Amenities -->
            <a href="{{ route('admin.manage-property.edit-amenities', ['id' => $propId]) }}" 
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold transition {{ $currentRoute === 'admin.manage-property.edit-amenities' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <i class="bi bi-stars"></i>
                <span>4. Amenities</span>
            </a>

            <i class="bi bi-chevron-right text-slate-300 dark:text-slate-700 text-xs"></i>

            <!-- 5. Floorplan -->
            <a href="{{ route('admin.manage-property.edit-floorplan', ['id' => $propId]) }}" 
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold transition {{ $currentRoute === 'admin.manage-property.edit-floorplan' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <i class="bi bi-bounding-box"></i>
                <span>5. Floorplan</span>
            </a>

            <i class="bi bi-chevron-right text-slate-300 dark:text-slate-700 text-xs"></i>

            <!-- 6. Offerings -->
            <a href="{{ route('admin.manage-property.edit-property-offerings', ['id' => $propId]) }}" 
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold transition {{ $currentRoute === 'admin.manage-property.edit-property-offerings' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <i class="bi bi-tag"></i>
                <span>6. Offerings</span>
            </a>

            <i class="bi bi-chevron-right text-slate-300 dark:text-slate-700 text-xs"></i>

            <!-- 7. Documents -->
            <a href="{{ route('admin.manage-property.edit-property-documents', ['id' => $propId]) }}" 
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold transition {{ $currentRoute === 'admin.manage-property.edit-property-documents' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <i class="bi bi-file-earmark-pdf"></i>
                <span>7. Documents</span>
            </a>

            <i class="bi bi-chevron-right text-slate-300 dark:text-slate-700 text-xs"></i>

            <!-- 8. Metrics -->
            <a href="{{ route('admin.manage-property.edit-property-metrics', ['id' => $propId]) }}" 
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold transition {{ $currentRoute === 'admin.manage-property.edit-property-metrics' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <i class="bi bi-graph-up-arrow"></i>
                <span>8. Underwriting & Metrics</span>
            </a>
        </nav>
    </div>
</div>
