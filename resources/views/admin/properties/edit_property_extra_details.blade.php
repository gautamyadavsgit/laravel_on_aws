@extends('admin.common.page')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
            <i class="bi bi-plus-square text-indigo-600 dark:text-indigo-400"></i> Edit Extra Property Details
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Configure deed fractions, leverage financing, and rental metrics for property #{{ $property_id }}.</p>
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
                <i class="bi bi-file-earmark-ruled text-indigo-600 dark:text-indigo-400"></i> Deed & Leverage Parameters
            </h2>
            <span class="badge-tw badge-info-tw">Property ID #{{ $property_id }}</span>
        </div>

        <form action="{{ route('admin.manage-property.update-property-extra-details', ['id' => $property_id]) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <div class="sm:col-span-2 md:col-span-1">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="deed_fraction_1">Deed Fraction 1</label>
                    <input class="input-tw" value="{{ $PropertyExtraDetails->deed_fraction_1 ?? old('deed_fraction_1') }}" type="text" name="deed_fraction_1" id="deed_fraction_1" placeholder="e.g. 1/1000th Ownership">
                </div>

                <div class="sm:col-span-2 md:col-span-1">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="deed_fraction_2">Deed Fraction 2</label>
                    <input class="input-tw" type="text" value="{{ $PropertyExtraDetails->deed_fraction_2 ?? old('deed_fraction_2') }}" name="deed_fraction_2" id="deed_fraction_2" placeholder="e.g. Secondary Unit">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="leveraged">Leveraged Debt Financing</label>
                    <select class="select-tw" name="leveraged" id="leveraged">
                        <option value="1" {{ ($PropertyExtraDetails->leveraged ?? old('leveraged')) ? 'selected' : '' }}>Yes (Leveraged)</option>
                        <option value="0" {{ !($PropertyExtraDetails->leveraged ?? old('leveraged')) ? 'selected' : '' }}>No (All Cash)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="leverage_amount">Leverage Principal Amount ($)</label>
                    <input class="input-tw" value="{{ $PropertyExtraDetails->leverage_amount ?? old('leverage_amount') }}" type="number" name="leverage_amount" id="leverage_amount" placeholder="350000">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="leverage_percent">Leverage Percentage (LTV %)</label>
                    <input class="input-tw" value="{{ $PropertyExtraDetails->leverage_percent ?? old('leverage_percent') }}" type="number" step="0.01" name="leverage_percent" id="leverage_percent" placeholder="50.00">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="rent_rate">Base Monthly Rent ($)</label>
                    <input class="input-tw" value="{{ $PropertyExtraDetails->rent_rate ?? old('rent_rate') }}" type="number" name="rent_rate" id="rent_rate" placeholder="4200">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="market_rent_rate">Projected Market Rent ($)</label>
                    <input class="input-tw" value="{{ $PropertyExtraDetails->market_rent_rate ?? old('market_rent_rate') }}" type="number" name="market_rent_rate" id="market_rent_rate" placeholder="4800">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="occupancy_rate">Historical Occupancy (%)</label>
                    <input class="input-tw" value="{{ $PropertyExtraDetails->occupancy_rate ?? old('occupancy_rate') }}" type="number" step="0.01" name="occupancy_rate" id="occupancy_rate" placeholder="88.5">
                </div>

                <div class="sm:col-span-2 md:col-span-1">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="occupancy_status">Occupancy Status</label>
                    <select class="select-tw" name="occupancy_status" id="occupancy_status">
                        <option value="yes" {{ ($PropertyExtraDetails->occupancy_status ?? old('occupancy_status')) == 'yes' ? 'selected' : '' }}>Occupied / Generating Income</option>
                        <option value="no" {{ ($PropertyExtraDetails->occupancy_status ?? old('occupancy_status')) == 'no' ? 'selected' : '' }}>Vacant / Pre-Launch</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="btn-primary-tw">
                    <i class="bi bi-check-lg"></i> Update Extra Details
                </button>
                <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
