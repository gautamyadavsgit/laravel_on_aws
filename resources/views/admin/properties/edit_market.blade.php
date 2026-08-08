@extends('admin.common.page')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
            <i class="bi bi-graph-up text-indigo-600 dark:text-indigo-400"></i> Edit Property Market Details
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Configure market trends, local attractions, and tax rates for property #{{ $property_id }}.</p>
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
                <i class="bi bi-bar-chart-line text-indigo-600 dark:text-indigo-400"></i> Market Overview & Taxes
            </h2>
            <span class="badge-tw badge-info-tw">Property ID #{{ $property_id }}</span>
        </div>

        <form action="{{ route('admin.manage-property.update-market', ['id' => $property_id]) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="market_title">
                        Market Headline / Title <span class="text-rose-500">*</span>
                    </label>
                    <input class="input-tw" value="{{ $propertyMarket->market_title ?? old('market_title') }}" required type="text" name="market_title" id="market_title" placeholder="e.g. Smoky Mountains Tourism Hub">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="market_image">
                        Market Image / Chart <span class="text-rose-500">*</span>
                    </label>
                    <input class="input-tw file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" type="file" required name="market_image" accept="image/*" id="market_image">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="market_description">
                        Market Dynamics Description <span class="text-rose-500">*</span>
                    </label>
                    <textarea class="textarea-tw" required name="market_description" id="market_description" rows="3" placeholder="Provide details on seasonal occupancy, tourism drivers, and regional appreciation...">{{ $propertyMarket->market_description ?? old('market_description') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="tax_1">Tax Indicator 1</label>
                    <input class="input-tw" value="{{ $propertyMarket->tax_1 ?? old('tax_1') }}" type="text" name="tax_1" id="tax_1" placeholder="e.g. 1.25% County Tax">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="tax_2">Tax Indicator 2</label>
                    <input class="input-tw" value="{{ $propertyMarket->tax_2 ?? old('tax_2') }}" type="text" name="tax_2" id="tax_2" placeholder="e.g. 0.5% City Tax">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="tax_3">Tax Indicator 3</label>
                    <input class="input-tw" value="{{ $propertyMarket->tax_3 ?? old('tax_3') }}" type="text" name="tax_3" id="tax_3" placeholder="e.g. Special Tourism Assessment">
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="btn-primary-tw">
                    <i class="bi bi-check-lg"></i> Update Market Details
                </button>
                <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
