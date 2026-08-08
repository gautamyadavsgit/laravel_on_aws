@extends('admin.common.page')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
            <i class="bi bi-bounding-box text-indigo-600 dark:text-indigo-400"></i> Edit Floor Plans
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Upload architectural schematics and layout floor plans for property #{{ $property_id }}.</p>
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
                <i class="bi bi-diagram-3 text-indigo-600 dark:text-indigo-400"></i> Floor Plan Uploads
            </h2>
            <span class="badge-tw badge-info-tw">Property ID #{{ $property_id }}</span>
        </div>

        <form action="{{ route('admin.manage-property.update-floorplan', ['id' => $property_id]) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="floor_plan_1">
                        Floor Plan Level 1 <span class="text-rose-500">*</span>
                    </label>
                    <input class="input-tw file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" type="file" required name="floor_plan_1" accept="image/*" id="floor_plan_1">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="floor_plan_2">
                        Floor Plan Level 2 <span class="text-rose-500">*</span>
                    </label>
                    <input class="input-tw file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" type="file" required name="floor_plan_2" accept="image/*" id="floor_plan_2">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="floor_plan_3">
                        Floor Plan Level 3 / Site Map <span class="text-rose-500">*</span>
                    </label>
                    <input class="input-tw file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" type="file" required name="floor_plan_3" accept="image/*" id="floor_plan_3">
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="btn-primary-tw">
                    <i class="bi bi-check-lg"></i> Update Floor Plans
                </button>
                <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
