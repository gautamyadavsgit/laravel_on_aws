@extends('admin.common.page')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
            <i class="bi bi-pie-chart text-indigo-600 dark:text-indigo-400"></i> Edit Fractional Shares & Deeds
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Configure tokenized fractional share deeds, prices, and dividend distribution dates for property #{{ $property_id }}.</p>
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
                <i class="bi bi-share text-indigo-600 dark:text-indigo-400"></i> Share Allocation & Distributions
            </h2>
            <span class="badge-tw badge-info-tw">Property ID #{{ $property_id }}</span>
        </div>

        <form action="{{ route('admin.manage-property.update-property-shares', ['id' => $property_id]) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <div class="sm:col-span-2 md:col-span-1">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="equity_raise">Equity Raise Target ($)</label>
                    <input class="input-tw" value="{{ $propertyAacf->equity_raise ?? old('equity_raise') }}" type="number" name="equity_raise" id="equity_raise" placeholder="617000">
                </div>

                <div class="sm:col-span-2 md:col-span-1">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="price_per_share_deed">Price Per Share ($)</label>
                    <input class="input-tw" type="number" step="0.01" value="{{ $propertyAacf->price_per_share_deed ?? old('price_per_share_deed') }}" name="price_per_share_deed" id="price_per_share_deed" placeholder="50.00">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="total_developer_share_deeds">Developer Retained Shares</label>
                    <input class="input-tw" value="{{ $propertyAacf->total_developer_share_deeds ?? old('total_developer_share_deeds') }}" type="number" name="total_developer_share_deeds" id="total_developer_share_deeds" placeholder="1000">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="total_raise_share_deeds">Public Raise Share Deeds</label>
                    <input class="input-tw" value="{{ $propertyAacf->total_raise_share_deeds ?? old('total_raise_share_deeds') }}" type="number" name="total_raise_share_deeds" id="total_raise_share_deeds" placeholder="11340">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="total_share_deeds">Total Minted Share Deeds</label>
                    <input class="input-tw" value="{{ $propertyAacf->total_share_deeds ?? old('total_share_deeds') }}" type="number" name="total_share_deeds" id="total_share_deeds" placeholder="12340">
                </div>

                <div class="sm:col-span-2 md:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="first_dividend_date">First Dividend Distribution Date</label>
                        <input class="input-tw" value="{{ $propertyAacf->first_dividend_date ?? old('first_dividend_date') }}" type="date" name="first_dividend_date" id="first_dividend_date">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="seccond_dividend_date">Second Dividend Distribution Date</label>
                        <input class="input-tw" value="{{ $propertyAacf->seccond_dividend_date ?? old('seccond_dividend_date') }}" type="date" name="seccond_dividend_date" id="seccond_dividend_date">
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="btn-primary-tw">
                    <i class="bi bi-check-lg"></i> Update Share Structure
                </button>
                <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
