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
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">
                        Stage 8: Financial Underwriting & Investor Goal Metrics
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Underwrite cash flows, capitalization yields, 5-yr growth projections, MACRS tax depreciation, and investor suitability ratings.</p>
                </div>
            </div>
            <span class="badge-tw badge-info-tw hidden sm:inline-flex">Step 8 of 8</span>
        </div>

        <form action="{{ route('admin.manage-property.update-property-metrics', ['id' => $property_id]) }}" method="POST" class="p-6 space-y-8">
            @csrf

            <!-- Quick Auto-Calculation Action Bar -->
            @php
                $baseVal = $property->propertyDetails->value ?? $property->propertyOffering->offering_purchase ?? 600000;
            @endphp
            <div class="p-4 rounded-xl bg-indigo-50/60 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-lg shrink-0 shadow-sm">
                        <i class="bi bi-calculator"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-indigo-950 dark:text-indigo-200">Asset Valuation Baseline: ${{ number_format((float) $baseVal) }}</h3>
                        <p class="text-xs text-indigo-700 dark:text-indigo-400">Derived from Property Details and Offering underwritten targets.</p>
                    </div>
                </div>
                <button type="button" onclick="autoCalculateMetrics({{ (float) $baseVal }})" class="btn-secondary-tw text-xs py-2 px-3.5 flex items-center justify-center gap-1.5 shrink-0 bg-white dark:bg-slate-900 shadow-sm hover:border-indigo-500">
                    <i class="bi bi-arrow-repeat text-indigo-600"></i>
                    <span>Recalculate Formulas</span>
                </button>
            </div>

            <!-- SECTION 1: Cash Flow & Operating Yields -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-2">
                    <i class="bi bi-cash-stack text-indigo-600 dark:text-indigo-400 text-lg"></i>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white">1. Cash Flow & Yield Underwriting</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="gross_annual_rent">
                            Gross Annual Rent ($) <span class="text-rose-500">*</span>
                        </label>
                        <input class="input-tw" type="number" step="0.01" name="gross_annual_rent" id="gross_annual_rent" value="{{ $propertyMetrics->gross_annual_rent ?? old('gross_annual_rent', round($baseVal * 0.112, 2)) }}" required>
                        <span class="text-[11px] text-slate-400">Total projected yearly rental revenue</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="operating_expenses">
                            Annual Operating Expenses ($) <span class="text-rose-500">*</span>
                        </label>
                        <input class="input-tw" type="number" step="0.01" name="operating_expenses" id="operating_expenses" value="{{ $propertyMetrics->operating_expenses ?? old('operating_expenses', round($baseVal * 0.112 * 0.23, 2)) }}" required>
                        <span class="text-[11px] text-slate-400">Management, maintenance, cleaning & HOA</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="net_operating_income">
                            Net Operating Income (NOI) ($)
                        </label>
                        <input class="input-tw bg-slate-50 dark:bg-slate-800" type="number" step="0.01" name="net_operating_income" id="net_operating_income" value="{{ $propertyMetrics->net_operating_income ?? old('net_operating_income') }}" readonly>
                        <span class="text-[11px] text-slate-400">Gross Rent minus Operating Expenses</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="cap_rate">
                            Cap Rate (%) <span class="text-rose-500">*</span>
                        </label>
                        <input class="input-tw" type="number" step="0.01" name="cap_rate" id="cap_rate" value="{{ $propertyMetrics->cap_rate ?? old('cap_rate', 8.62) }}" required>
                        <span class="text-[11px] text-slate-400">Annual NOI / Asset Value</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="annual_cash_flow">
                            Net Distributable Cash Flow ($)
                        </label>
                        <input class="input-tw" type="number" step="0.01" name="annual_cash_flow" id="annual_cash_flow" value="{{ $propertyMetrics->annual_cash_flow ?? old('annual_cash_flow') }}">
                        <span class="text-[11px] text-slate-400">Net dividend distributed to shareholders</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="cash_on_cash_return">
                            Cash-on-Cash Return (%)
                        </label>
                        <input class="input-tw" type="number" step="0.01" name="cash_on_cash_return" id="cash_on_cash_return" value="{{ $propertyMetrics->cash_on_cash_return ?? old('cash_on_cash_return', 8.25) }}">
                        <span class="text-[11px] text-slate-400">Annual net dividend yield %</span>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Capital Appreciation & IRR -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-2">
                    <i class="bi bi-trending-up text-indigo-600 dark:text-indigo-400 text-lg"></i>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white">2. Long-Term Appreciation & Valuation Forecasts</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="estimated_appreciation_rate">
                            Est. Annual Appreciation (%)
                        </label>
                        <input class="input-tw" type="number" step="0.01" name="estimated_appreciation_rate" id="estimated_appreciation_rate" value="{{ $propertyMetrics->estimated_appreciation_rate ?? old('estimated_appreciation_rate', 5.20) }}">
                        <span class="text-[11px] text-slate-400">Historical market CAGR benchmark</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="projected_value_5yr">
                            5-Year Projected Value ($)
                        </label>
                        <input class="input-tw" type="number" step="0.01" name="projected_value_5yr" id="projected_value_5yr" value="{{ $propertyMetrics->projected_value_5yr ?? old('projected_value_5yr') }}">
                        <span class="text-[11px] text-slate-400">Compounded 5-year asset valuation</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="projected_value_10yr">
                            10-Year Projected Value ($)
                        </label>
                        <input class="input-tw" type="number" step="0.01" name="projected_value_10yr" id="projected_value_10yr" value="{{ $propertyMetrics->projected_value_10yr ?? old('projected_value_10yr') }}">
                        <span class="text-[11px] text-slate-400">Compounded 10-year asset valuation</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="projected_irr">
                            Projected 5-Yr IRR (%)
                        </label>
                        <input class="input-tw" type="number" step="0.01" name="projected_irr" id="projected_irr" value="{{ $propertyMetrics->projected_irr ?? old('projected_irr', 14.62) }}">
                        <span class="text-[11px] text-slate-400">Total internal rate of return</span>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Tax Efficiency & Shielding -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-2">
                    <i class="bi bi-shield-check text-indigo-600 dark:text-indigo-400 text-lg"></i>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white">3. Tax Deductions & IRS 1031 Exchange Attributes</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="annual_depreciation_deduction">
                            Annual Depreciation ($)
                        </label>
                        <input class="input-tw" type="number" step="0.01" name="annual_depreciation_deduction" id="annual_depreciation_deduction" value="{{ $propertyMetrics->annual_depreciation_deduction ?? old('annual_depreciation_deduction') }}">
                        <span class="text-[11px] text-slate-400">27.5-Year residential depreciation</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="tax_savings_estimate">
                            Est. Annual Tax Savings ($)
                        </label>
                        <input class="input-tw" type="number" step="0.01" name="tax_savings_estimate" id="tax_savings_estimate" value="{{ $propertyMetrics->tax_savings_estimate ?? old('tax_savings_estimate') }}">
                        <span class="text-[11px] text-slate-400">Tax shield at 32% marginal bracket</span>
                    </div>

                    <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 flex items-center gap-3">
                        <input type="checkbox" name="is_1031_exchange_eligible" id="is_1031_exchange_eligible" value="1" {{ ($propertyMetrics->is_1031_exchange_eligible ?? true) ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                        <label for="is_1031_exchange_eligible" class="text-xs font-semibold text-slate-800 dark:text-slate-200 cursor-pointer">
                            1031 Exchange Qualified
                            <span class="block text-[10px] text-slate-400 font-normal">Allows tax-deferred equity rollover</span>
                        </label>
                    </div>

                    <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 flex items-center gap-3">
                        <input type="checkbox" name="cost_segregation_eligible" id="cost_segregation_eligible" value="1" {{ ($propertyMetrics->cost_segregation_eligible ?? true) ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                        <label for="cost_segregation_eligible" class="text-xs font-semibold text-slate-800 dark:text-slate-200 cursor-pointer">
                            Cost Segregation Ready
                            <span class="block text-[10px] text-slate-400 font-normal">Accelerates Year-1 bonus depreciation</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: Diversification & Investor Goal Suitability Scores -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-2">
                    <i class="bi bi-compass text-indigo-600 dark:text-indigo-400 text-lg"></i>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white">4. Portfolio Diversification & Investor Goal Match Scores</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="diversification_score">
                            Diversification Score (/10)
                        </label>
                        <input class="input-tw" type="number" step="0.1" max="10" min="0" name="diversification_score" id="diversification_score" value="{{ $propertyMetrics->diversification_score ?? old('diversification_score', 8.8) }}">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="occupancy_rate_projected">
                            Projected Occupancy (%)
                        </label>
                        <input class="input-tw" type="number" step="0.01" max="100" min="0" name="occupancy_rate_projected" id="occupancy_rate_projected" value="{{ $propertyMetrics->occupancy_rate_projected ?? old('occupancy_rate_projected', 86.50) }}">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="cash_flow_rating">
                            Cash Flow Score (0-100)
                        </label>
                        <input class="input-tw" type="number" max="100" min="0" name="cash_flow_rating" id="cash_flow_rating" value="{{ $propertyMetrics->cash_flow_rating ?? old('cash_flow_rating', 92) }}">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="appreciation_rating">
                            Appreciation Score (0-100)
                        </label>
                        <input class="input-tw" type="number" max="100" min="0" name="appreciation_rating" id="appreciation_rating" value="{{ $propertyMetrics->appreciation_rating ?? old('appreciation_rating', 88) }}">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="tax_benefit_rating">
                            Tax Advantage Score (0-100)
                        </label>
                        <input class="input-tw" type="number" max="100" min="0" name="tax_benefit_rating" id="tax_benefit_rating" value="{{ $propertyMetrics->tax_benefit_rating ?? old('tax_benefit_rating', 94) }}">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="diversification_rating">
                            Diversification Score (0-100)
                        </label>
                        <input class="input-tw" type="number" max="100" min="0" name="diversification_rating" id="diversification_rating" value="{{ $propertyMetrics->diversification_rating ?? old('diversification_rating', 90) }}">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="calculation_notes">
                            Underwriting & Analysis Notes
                        </label>
                        <input class="input-tw" type="text" name="calculation_notes" id="calculation_notes" value="{{ $propertyMetrics->calculation_notes ?? old('calculation_notes', 'Auto-underwritten based on property valuation and historical regional vacation rental yield benchmarks.') }}" placeholder="Notes for investors">
                    </div>
                </div>
            </div>

            <!-- Complete Action Buttons -->
            <div class="border-t border-slate-200 dark:border-slate-800 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                <a href="{{ route('admin.manage-property.edit-property-documents', ['id' => $property_id]) }}" class="btn-secondary-tw w-full sm:w-auto text-center order-2 sm:order-1">
                    <i class="bi bi-arrow-left"></i> Previous Step (Documents)
                </a>
                <div class="flex items-center gap-2.5 w-full sm:w-auto order-1 sm:order-2">
                    <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw w-full sm:w-auto text-center">
                        <i class="bi bi-list-task"></i> View All Properties
                    </a>
                    <button type="submit" class="btn-primary-tw w-full sm:w-auto flex items-center justify-center gap-2 shadow-md">
                        <i class="bi bi-check2-circle text-base"></i> Save & Complete Property Setup
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function autoCalculateMetrics(baseVal) {
    const rentInput = document.getElementById('gross_annual_rent');
    const expInput = document.getElementById('operating_expenses');
    const noiInput = document.getElementById('net_operating_income');
    const capInput = document.getElementById('cap_rate');
    const flowInput = document.getElementById('annual_cash_flow');
    const cocInput = document.getElementById('cash_on_cash_return');
    const appRateInput = document.getElementById('estimated_appreciation_rate');
    const val5Input = document.getElementById('projected_value_5yr');
    const val10Input = document.getElementById('projected_value_10yr');
    const irrInput = document.getElementById('projected_irr');
    const depInput = document.getElementById('annual_depreciation_deduction');
    const taxInput = document.getElementById('tax_savings_estimate');

    const grossRent = Math.round(baseVal * 0.112 * 100) / 100;
    const expenses = Math.round(grossRent * 0.23 * 100) / 100;
    const noi = Math.round((grossRent - expenses) * 100) / 100;
    const capRate = Math.round((noi / baseVal) * 10000) / 100;
    const cashFlow = Math.round((noi - (grossRent * 0.03)) * 100) / 100;
    const coc = Math.round((cashFlow / baseVal) * 10000) / 100;
    const appRate = parseFloat(appRateInput.value) || 5.20;
    const val5 = Math.round(baseVal * Math.pow(1 + (appRate / 100), 5) * 100) / 100;
    const val10 = Math.round(baseVal * Math.pow(1 + (appRate / 100), 10) * 100) / 100;
    const irr = Math.round((capRate + appRate + 0.8) * 100) / 100;
    const dep = Math.round(((baseVal * 0.85) / 27.5) * 100) / 100;
    const taxSav = Math.round(dep * 0.32 * 100) / 100;

    rentInput.value = grossRent;
    expInput.value = expenses;
    noiInput.value = noi;
    capInput.value = capRate;
    flowInput.value = cashFlow;
    cocInput.value = coc;
    val5Input.value = val5;
    val10Input.value = val10;
    irrInput.value = irr;
    depInput.value = dep;
    taxInput.value = taxSav;
}

// Auto compute NOI live on rent/expense changes
document.addEventListener('DOMContentLoaded', () => {
    const rent = document.getElementById('gross_annual_rent');
    const exp = document.getElementById('operating_expenses');
    const noi = document.getElementById('net_operating_income');

    function updateNoi() {
        const r = parseFloat(rent.value) || 0;
        const e = parseFloat(exp.value) || 0;
        noi.value = Math.max(0, r - e).toFixed(2);
    }

    if (rent && exp && noi) {
        rent.addEventListener('input', updateNoi);
        exp.addEventListener('input', updateNoi);
        updateNoi();
    }
});
</script>
@endsection
