@extends('admin.common.page')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
            <i class="bi bi-receipt text-indigo-600 dark:text-indigo-400"></i> Edit Property Taxes
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Configure real estate property tax schedules and assessments for property #{{ $property_id }}.</p>
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
                <i class="bi bi-percent text-indigo-600 dark:text-indigo-400"></i> Tax Schedules (Taxes 1 - 3)
            </h2>
            <span class="badge-tw badge-info-tw">Property ID #{{ $property_id }}</span>
        </div>

        <form action="{{ route('admin.manage-property.update-property-taxes', ['id' => $property_id]) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @for ($i = 1; $i <= 3; $i++)
                    @php
                        $taxKey = 'tax_' . $i;
                        $taxValue = isset($propertyTax[$i - 1]->tax_key) && $propertyTax[$i - 1]->tax_key === $taxKey ? $propertyTax[$i - 1]->tax_value : old($taxKey);
                    @endphp
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="{{ $taxKey }}">
                            Tax Schedule {{ $i }} ($ / Year)
                        </label>
                        <input class="input-tw" value="{{ $taxValue }}" type="number" step="any" name="{{ $taxKey }}" id="{{ $taxKey }}" placeholder="3200">
                    </div>
                @endfor
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="btn-primary-tw">
                    <i class="bi bi-check-lg"></i> Update Property Taxes
                </button>
                <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
