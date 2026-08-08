@extends('admin.common.page')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
            <i class="bi bi-currency-dollar text-indigo-600 dark:text-indigo-400"></i> Edit Financial Management Details
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Configure asset management fees, cash reserves, hold horizons, and appreciation targets for property #{{ $property_id }}.</p>
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
                <i class="bi bi-wallet2 text-indigo-600 dark:text-indigo-400"></i> Asset Management & Reserves
            </h2>
            <span class="badge-tw badge-info-tw">Property ID #{{ $property_id }}</span>
        </div>

        <form action="{{ route('admin.manage-property.update-financial-details', ['id' => $property_id]) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="management_fee">Management Fee (%)</label>
                    <input class="input-tw" value="{{ $propertyAacf->management_fee ?? old('management_fee') }}" type="number" step="0.01" name="management_fee" id="management_fee" placeholder="10.00">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="cash_reserve">Operational Cash Reserve ($)</label>
                    <input class="input-tw" type="number" value="{{ $propertyAacf->cash_reserve ?? old('cash_reserve') }}" name="cash_reserve" id="cash_reserve" placeholder="20000">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="hold_period">Target Hold Period (Years)</label>
                    <input class="input-tw" value="{{ $propertyAacf->hold_period ?? old('hold_period') }}" type="number" name="hold_period" id="hold_period" placeholder="5">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="annual_appreciation">Expected Annual Appreciation (%)</label>
                    <input class="input-tw" value="{{ $propertyAacf->annual_appreciation ?? old('annual_appreciation') }}" type="number" step="0.01" name="annual_appreciation" id="annual_appreciation" placeholder="5.50">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="aum_fee_1">AUM Surcharge Tier 1 ($)</label>
                    <input class="input-tw" value="{{ $propertyAacf->aum_fee_1 ?? old('aum_fee_1') }}" type="number" name="aum_fee_1" id="aum_fee_1" placeholder="1500">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="aum_fee_2">AUM Surcharge Tier 2 ($)</label>
                    <input class="input-tw" value="{{ $propertyAacf->aum_fee_2 ?? old('aum_fee_2') }}" type="number" name="aum_fee_2" id="aum_fee_2" placeholder="2500">
                </div>

                <div class="sm:col-span-2 md:col-span-1">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="aum_fee_3">AUM Surcharge Tier 3 ($)</label>
                    <input class="input-tw" value="{{ $propertyAacf->aum_fee_3 ?? old('aum_fee_3') }}" type="number" name="aum_fee_3" id="aum_fee_3" placeholder="3500">
                </div>

                <div class="sm:col-span-2 md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="average_time_to_rent">Average Time To Rent (Days)</label>
                    <input class="input-tw" value="{{ $propertyAacf->average_time_to_rent ?? old('average_time_to_rent') }}" type="number" name="average_time_to_rent" id="average_time_to_rent" placeholder="14">
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="btn-primary-tw">
                    <i class="bi bi-check-lg"></i> Update Financial Parameters
                </button>
                <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
