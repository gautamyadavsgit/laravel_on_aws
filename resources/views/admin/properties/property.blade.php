@extends('admin.common.page')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
            <i class="bi bi-building text-indigo-600 dark:text-indigo-400"></i> {{ $property->name ?? 'Property Overview' }}
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Detailed media, floorplans, and documentation overview for property #{{ $property->id }}.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('manage-property.edit', ['manage_property' => $property->id]) }}" class="btn-primary-tw">
            <i class="bi bi-pencil"></i> Edit Property
        </a>
        <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<div class="space-y-6">
    <!-- Property Images Gallery -->
    <div class="card-tw">
        <div class="card-header-tw">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-images text-indigo-600 dark:text-indigo-400"></i> Uploaded Property Gallery
            </h2>
            <span class="badge-tw badge-info-tw">{{ count($property->propertyImage ?? []) }} Images</span>
        </div>
        <div class="flex flex-wrap gap-4">
            @forelse ($property->propertyImage as $image)
                @if ($image->property_image_key == 'property_image')
                    <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-800/50 w-56 shadow-sm">
                        <a target="_blank" href="{{ asset('storage/' . $image->property_image_value) }}">
                            <img class="w-full h-40 object-cover hover:scale-105 transition duration-300" src="{{ asset('storage/' . $image->property_image_value) }}" alt="Property Image" onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'800\' height=\'600\' viewBox=\'0 0 800 600\'><rect width=\'800\' height=\'600\' fill=\'%23312e81\'/><text x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-family=\'sans-serif\' font-size=\'24\' fill=\'%23ffffff\'>Luxury Real Estate</text></svg>';">
                        </a>
                    </div>
                @endif
            @empty
                <div class="text-slate-400 dark:text-slate-500 p-8 text-center w-full">
                    <i class="bi bi-image text-3xl block mb-2"></i> No property images uploaded yet.
                </div>
            @endforelse
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Floorplans -->
        <div class="card-tw">
            <div class="card-header-tw">
                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-bounding-box text-indigo-600 dark:text-indigo-400"></i> Floor Plans & Schematics
                </h2>
            </div>
            <div class="flex flex-wrap gap-4">
                @forelse ($property->propertyFloorplan ?? [] as $plan)
                    <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-800/50 w-44 shadow-sm">
                        <a target="_blank" href="{{ asset('storage/' . $plan->value) }}">
                            <img class="w-full h-32 object-cover hover:scale-105 transition duration-300" src="{{ asset('storage/' . $plan->value) }}" alt="Floor Plan" onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'800\' height=\'600\' viewBox=\'0 0 800 600\'><rect width=\'800\' height=\'600\' fill=\'%231e293b\'/><text x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-family=\'sans-serif\' font-size=\'22\' fill=\'%2338bdf8\'>Architectural Blueprint</text></svg>';">
                        </a>
                    </div>
                @empty
                    <div class="text-slate-400 dark:text-slate-500 p-8 text-center w-full">
                        <i class="bi bi-diagram-3 text-3xl block mb-2"></i> No floor plans attached.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Legal Documents -->
        <div class="card-tw">
            <div class="card-header-tw">
                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-file-earmark-pdf text-indigo-600 dark:text-indigo-400"></i> Legal Documentation
                </h2>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse ($property->propertyDocumentModel ?? [] as $doc)
                    <a target="_blank" href="{{ asset('storage/' . $doc->document_value) }}" class="flex items-center justify-between py-3 px-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/60 transition text-sm">
                        <div class="flex items-center gap-2.5">
                            <i class="bi bi-file-earmark-pdf-fill text-rose-600 text-lg"></i>
                            <span class="font-medium text-slate-700 dark:text-slate-200">{{ ucfirst(str_replace('_', ' ', $doc->document_key)) }}</span>
                        </div>
                        <i class="bi bi-box-arrow-up-right text-xs text-slate-400"></i>
                    </a>
                @empty
                    <div class="text-slate-400 dark:text-slate-500 p-8 text-center">
                        <i class="bi bi-folder-x text-3xl block mb-2"></i> No PDF documents attached.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
