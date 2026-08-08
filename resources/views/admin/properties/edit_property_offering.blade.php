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
                    <i class="bi bi-tag"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">
                        Stage 6: Capital Offering & Cost Breakdown
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Underwriting breakdown, acquisition cost, renovation budget, and escrow fees.</p>
                </div>
            </div>
            <span class="badge-tw badge-info-tw hidden sm:inline-flex">Step 6 of 7</span>
        </div>

        <form action="{{ route('admin.manage-property.update-property-offerings', ['id' => $property_id]) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="offering_purchase">
                        Purchase Price ($) <span class="text-rose-500">*</span>
                    </label>
                    <input class="input-tw" value="{{ $propertyOffering->offering_purchase ?? old('offering_purchase') }}" type="number" name="offering_purchase" id="offering_purchase" placeholder="650000" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="offering_build_cost">
                        Build / Renovation Costs ($)
                    </label>
                    <input class="input-tw" type="number" value="{{ $propertyOffering->offering_build_cost ?? old('offering_build_cost') }}" name="offering_build_cost" id="offering_build_cost" placeholder="45000">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="offering_improvements">
                        Capital Improvements ($)
                    </label>
                    <input class="input-tw" value="{{ $propertyOffering->offering_improvements ?? old('offering_improvements') }}" type="number" name="offering_improvements" id="offering_improvements" placeholder="25000">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="offering_closing_cost">
                        Closing & Escrow Costs ($)
                    </label>
                    <input class="input-tw" value="{{ $propertyOffering->offering_closing_cost ?? old('offering_closing_cost') }}" type="number" name="offering_closing_cost" id="offering_closing_cost" placeholder="14000">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="offering_sourcing_fees">
                        Acquisition & Deal Sourcing Fees ($)
                    </label>
                    <input class="input-tw" value="{{ $propertyOffering->offering_sourcing_fees ?? old('offering_sourcing_fees') }}" type="number" name="offering_sourcing_fees" id="offering_sourcing_fees" placeholder="18000">
                </div>
            </div>

            <div class="border-t border-slate-200 dark:border-slate-800 pt-5 flex flex-col sm:flex-row items-center justify-between gap-3">
                <a href="{{ route('admin.manage-property.edit-floorplan', ['id' => $property_id]) }}" class="btn-secondary-tw w-full sm:w-auto text-center order-2 sm:order-1">
                    <i class="bi bi-arrow-left"></i> Previous Step
                </a>
                <div class="flex items-center gap-2.5 w-full sm:w-auto order-1 sm:order-2">
                    <button type="submit" class="btn-primary-tw w-full sm:w-auto flex items-center justify-center gap-2">
                        <i class="bi bi-check-lg text-base"></i> Save & Continue to Documents
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
