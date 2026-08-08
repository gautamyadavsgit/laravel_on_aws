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
                    <i class="bi bi-bounding-box"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">
                        Stage 5: Architectural Floor Plans
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Level schematics, architectural blueprints, and site map layouts.</p>
                </div>
            </div>
            <span class="badge-tw badge-info-tw hidden sm:inline-flex">Step 5 of 7</span>
        </div>

        <form action="{{ route('admin.manage-property.update-floorplan', ['id' => $property_id]) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Existing Floorplans Preview -->
            @if(isset($propertyFloorplan) && count($propertyFloorplan) > 0)
                <div class="border-b border-slate-200 dark:border-slate-800 pb-5">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-3">
                        Active Architectural Schematics ({{ count($propertyFloorplan) }} Plans)
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($propertyFloorplan as $index => $plan)
                            <div class="rounded-xl border border-slate-200 dark:border-slate-800 p-3 bg-slate-50 dark:bg-slate-900/60">
                                <div class="aspect-video rounded-lg overflow-hidden bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center mb-2">
                                    <img src="{{ asset('storage/' . $plan->value) }}" alt="{{ $plan->key }}" class="w-full h-full object-contain" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'60\' fill=\'%2364748b\'><rect width=\'100\' height=\'60\' fill=\'%23f1f5f9\'/><text x=\'50%\' y=\'50%\' font-size=\'8\' text-anchor=\'middle\' dy=\'.3em\'>Floorplan Preview</text></svg>'">
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-medium text-slate-800 dark:text-slate-200">{{ $plan->key ?? 'Level ' . ($index + 1) }}</span>
                                    <span class="badge-tw badge-info-tw text-[10px]">Active</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 space-y-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300" for="floor_plan_1">
                        Floor Plan Level 1 <span class="text-rose-500">*</span>
                    </label>
                    <input class="input-tw file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" type="file" required name="floor_plan_1" accept="image/*" id="floor_plan_1">
                    <p class="text-[11px] text-slate-400">Ground level & main entrance layout.</p>
                </div>

                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 space-y-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300" for="floor_plan_2">
                        Floor Plan Level 2 <span class="text-rose-500">*</span>
                    </label>
                    <input class="input-tw file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" type="file" required name="floor_plan_2" accept="image/*" id="floor_plan_2">
                    <p class="text-[11px] text-slate-400">Upper floor bedrooms & deck access.</p>
                </div>

                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 space-y-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300" for="floor_plan_3">
                        Level 3 / Site Plan <span class="text-rose-500">*</span>
                    </label>
                    <input class="input-tw file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" type="file" required name="floor_plan_3" accept="image/*" id="floor_plan_3">
                    <p class="text-[11px] text-slate-400">Basement or site boundaries.</p>
                </div>
            </div>

            <div class="border-t border-slate-200 dark:border-slate-800 pt-5 flex flex-col sm:flex-row items-center justify-between gap-3">
                <a href="{{ route('admin.manage-property.edit-amenities', ['id' => $property_id]) }}" class="btn-secondary-tw w-full sm:w-auto text-center order-2 sm:order-1">
                    <i class="bi bi-arrow-left"></i> Previous Step
                </a>
                <div class="flex items-center gap-2.5 w-full sm:w-auto order-1 sm:order-2">
                    <button type="submit" class="btn-primary-tw w-full sm:w-auto flex items-center justify-center gap-2">
                        <i class="bi bi-check-lg text-base"></i> Save & Continue to Offerings
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
