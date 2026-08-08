@extends('admin.common.page')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
            <i class="bi bi-cash-coin text-indigo-600 dark:text-indigo-400"></i> Edit Annual Cash Flow (AACF)
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Configure annualized rental income, gross yields, expenses, and net returns for property #{{ $property_id }}.</p>
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
                <i class="bi bi-calculator text-indigo-600 dark:text-indigo-400"></i> Cash Flow & Yield Model
            </h2>
            <span class="badge-tw badge-info-tw">Property ID #{{ $property_id }}</span>
        </div>

        <form action="{{ route('admin.manage-property.update-property-aacf', ['id' => $property_id]) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="annual_rent_amount">Annual Gross Rent ($)</label>
                    <input class="input-tw" value="{{ $propertyAacf->annual_rent_amount ?? old('annual_rent_amount') }}" type="number" name="annual_rent_amount" id="annual_rent_amount" placeholder="64000">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="annual_rent_gross_yield">Annual Gross Yield (%)</label>
                    <input class="input-tw" type="number" step="0.01" value="{{ $propertyAacf->annual_rent_gross_yield ?? old('annual_rent_gross_yield') }}" name="annual_rent_gross_yield" id="annual_rent_gross_yield" placeholder="9.25">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="aacf_expences">Total Annual Operating Expenses ($)</label>
                    <input class="input-tw" value="{{ $propertyAacf->aacf_expences ?? old('aacf_expences') }}" type="number" name="aacf_expences" id="aacf_expences" placeholder="14500">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="aacf_net">Annual Net Cash Flow ($)</label>
                    <input class="input-tw" value="{{ $propertyAacf->aacf_net ?? old('aacf_net') }}" type="number" name="aacf_net" id="aacf_net" placeholder="49500">
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="btn-primary-tw">
                    <i class="bi bi-check-lg"></i> Update Cash Flow Metrics
                </button>
                <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
