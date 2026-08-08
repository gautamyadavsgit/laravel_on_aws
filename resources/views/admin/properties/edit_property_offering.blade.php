@extends('admin.common.page')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
            <i class="bi bi-tag text-indigo-600 dark:text-indigo-400"></i> Edit Offering Structure
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Configure capital allocation, purchase price, build costs, and sourcing fees for property #{{ $property_id }}.</p>
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
                <i class="bi bi-cash-stack text-indigo-600 dark:text-indigo-400"></i> Capital Offering Breakdown
            </h2>
            <span class="badge-tw badge-info-tw">Property ID #{{ $property_id }}</span>
        </div>

        <form action="{{ route('admin.manage-property.update-property-offerings', ['id' => $property_id]) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="offering_purchase">Offering Purchase Price ($)</label>
                    <input class="input-tw" value="{{ $propertyAacf->offering_purchase ?? old('offering_purchase') }}" type="number" name="offering_purchase" id="offering_purchase" placeholder="520000">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="offering_build_cost">Build / Construction Costs ($)</label>
                    <input class="input-tw" type="number" value="{{ $propertyAacf->offering_build_cost ?? old('offering_build_cost') }}" name="offering_build_cost" id="offering_build_cost" placeholder="45000">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="offering_improvements">Capital Improvements ($)</label>
                    <input class="input-tw" value="{{ $propertyAacf->offering_improvements ?? old('offering_improvements') }}" type="number" name="offering_improvements" id="offering_improvements" placeholder="25000">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="offering_closing_cost">Closing & Escrow Costs ($)</label>
                    <input class="input-tw" value="{{ $propertyAacf->offering_closing_cost ?? old('offering_closing_cost') }}" type="number" name="offering_closing_cost" id="offering_closing_cost" placeholder="12000">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="offering_sourcing_fees">Acquisition & Sourcing Fees ($)</label>
                    <input class="input-tw" value="{{ $propertyAacf->offering_sourcing_fees ?? old('offering_sourcing_fees') }}" type="number" name="offering_sourcing_fees" id="offering_sourcing_fees" placeholder="15000">
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="btn-primary-tw">
                    <i class="bi bi-check-lg"></i> Update Offering Breakdown
                </button>
                <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
