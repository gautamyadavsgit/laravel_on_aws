@include('frontend.common.header', ['title' => 'Smoky Mountain Luxury Estate - Fractional Investment | Gautam Real Estate'])

@php
    $propId = request()->query('id');
    $query = \App\Models\PropertyModel::with(['propertyImage', 'propertyFloorplan', 'propertyDocumentModel', 'propertyAddress', 'propertyDetails', 'propertyAmenities', 'propertyMetrics']);
    $featuredProperty = $propId ? $query->find($propId) : $query->first();
    if (!$featuredProperty) {
        $featuredProperty = \App\Models\PropertyModel::with(['propertyImage', 'propertyFloorplan', 'propertyDocumentModel', 'propertyAddress', 'propertyDetails', 'propertyAmenities', 'propertyMetrics'])->first();
    }
    
    $propName = $featuredProperty->name ?? 'Smoky Mountain Luxury Cabin';
    $propDesc = $featuredProperty->description ?? 'Located in the high-demand vacation corridor of Gatlinburg, TN, this luxury short-term rental property features custom timber architecture, panoramic Great Smoky Mountain vistas, private hot tub deck, and dedicated entertainment suites.';
    $details = $featuredProperty->propertyDetails ?? null;
    $metrics = $featuredProperty->propertyMetrics ?? null;
    $beds = $details->bedrooms ?? 4;
    $baths = $details->baths ?? 3.5;
    $sqft = $details->square_feets ?? 2850;
    $year = $details->year_built ?? 2022;
    $val = $details->value ?? 617000;
    $images = $featuredProperty && count($featuredProperty->propertyImage ?? []) > 0 ? $featuredProperty->propertyImage : null;
@endphp

<div class="py-10 lg:py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Navigation -->
        <div class="mb-6">
            <a href="{{ url('invest') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                <i class="bi bi-arrow-left"></i> View All Investments
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Column: Gallery, Specs, Metrics, About, Financials -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Native Tailwind Image Carousel Slider -->
                <div id="propertySlider" class="relative rounded-2xl overflow-hidden bg-slate-900 shadow-lg group">
                    <div class="relative h-72 sm:h-96 md:h-[420px] w-full">
                        @if ($images)
                            @foreach ($images as $idx => $img)
                                <div data-carousel-item class="absolute inset-0 transition-opacity duration-300 {{ $idx > 0 ? 'opacity-0 pointer-events-none hidden' : '' }}">
                                    <img src="{{ asset('storage/' . $img->property_image_value) }}" alt="Property Image {{ $idx + 1 }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'1200\' height=\'800\' viewBox=\'0 0 1200 800\'><rect width=\'1200\' height=\'800\' fill=\'%23312e81\'/><text x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-family=\'sans-serif\' font-size=\'32\' fill=\'%23ffffff\'>Luxury Fractional Estate</text></svg>';">
                                </div>
                            @endforeach
                        @else
                            <div data-carousel-item class="absolute inset-0 transition-opacity duration-300">
                                <img src="{{ asset('storage/property_images/property_1.png') }}" alt="Estate Exterior" class="w-full h-full object-cover">
                            </div>
                            <div data-carousel-item class="absolute inset-0 transition-opacity duration-300 opacity-0 pointer-events-none hidden">
                                <img src="{{ asset('storage/property_images/property_2.png') }}" alt="Luxury Living Room" class="w-full h-full object-cover">
                            </div>
                            <div data-carousel-item class="absolute inset-0 transition-opacity duration-300 opacity-0 pointer-events-none hidden">
                                <img src="{{ asset('storage/property_images/property_3.png') }}" alt="Mountain View Deck" class="w-full h-full object-cover">
                            </div>
                        @endif
                    </div>

                    <!-- Slide Navigation Controls -->
                    <button type="button" data-carousel-prev class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-slate-900/60 backdrop-blur-sm text-white flex items-center justify-center hover:bg-slate-900 transition" aria-label="Previous Slide">
                        <i class="bi bi-chevron-left text-lg"></i>
                    </button>
                    <button type="button" data-carousel-next class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-slate-900/60 backdrop-blur-sm text-white flex items-center justify-center hover:bg-slate-900 transition" aria-label="Next Slide">
                        <i class="bi bi-chevron-right text-lg"></i>
                    </button>

                    <!-- Indicators -->
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2">
                        <button type="button" data-carousel-indicator class="h-2 rounded-full transition-all duration-300 bg-white w-8" aria-label="Slide 1"></button>
                        <button type="button" data-carousel-indicator class="h-2 rounded-full transition-all duration-300 bg-white/50 w-3" aria-label="Slide 2"></button>
                        <button type="button" data-carousel-indicator class="h-2 rounded-full transition-all duration-300 bg-white/50 w-3" aria-label="Slide 3"></button>
                    </div>
                </div>

                <!-- Basic Specs Bar -->
                <div class="card-tw p-5">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center divide-y sm:divide-y-0 sm:divide-x divide-slate-100 dark:divide-slate-800">
                        <div class="pt-2 sm:pt-0">
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">Bedrooms</span>
                            <strong class="text-lg font-bold text-slate-900 dark:text-white flex items-center justify-center gap-1.5 mt-0.5">
                                <i class="bi bi-door-closed text-indigo-600 dark:text-indigo-400"></i> {{ $beds }} Beds
                            </strong>
                        </div>
                        <div class="pt-2 sm:pt-0">
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">Bathrooms</span>
                            <strong class="text-lg font-bold text-slate-900 dark:text-white flex items-center justify-center gap-1.5 mt-0.5">
                                <i class="bi bi-droplet text-indigo-600 dark:text-indigo-400"></i> {{ $baths }} Baths
                            </strong>
                        </div>
                        <div class="pt-2 sm:pt-0">
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">Square Footage</span>
                            <strong class="text-lg font-bold text-slate-900 dark:text-white flex items-center justify-center gap-1.5 mt-0.5">
                                <i class="bi bi-aspect-ratio text-indigo-600 dark:text-indigo-400"></i> {{ number_format($sqft) }} SqFt
                            </strong>
                        </div>
                        <div class="pt-2 sm:pt-0">
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">Year Built</span>
                            <strong class="text-lg font-bold text-slate-900 dark:text-white flex items-center justify-center gap-1.5 mt-0.5">
                                <i class="bi bi-calendar3 text-indigo-600 dark:text-indigo-400"></i> {{ $year }}
                            </strong>
                        </div>
                    </div>
                </div>

                <!-- INSTITUTIONAL INVESTMENT METRICS & GOAL ALIGNMENT -->
                <div class="card-tw p-6 space-y-6 shadow-md border-t-4 border-t-indigo-600 dark:border-t-indigo-500">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="badge-tw badge-info-tw text-[10px] tracking-wider uppercase">Underwritten Asset</span>
                                <span class="text-xs text-slate-400">Audited Financial Metrics</span>
                            </div>
                            <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white mt-1">
                                Investment Goals & Financial Pro-Forma
                            </h2>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="text-right">
                                <span class="text-[11px] font-semibold uppercase text-slate-400 block">Cap Rate</span>
                                <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-400">{{ $metrics->cap_rate ?? '8.62' }}%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Goal Selector Tabs -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 bg-slate-100 dark:bg-slate-800/80 p-1.5 rounded-xl">
                        <button type="button" onclick="switchGoalTab('cash_flow')" id="tab-cash_flow" class="goal-tab-btn px-3 py-2 rounded-lg text-xs font-bold transition bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm flex items-center justify-center gap-1.5">
                            <i class="bi bi-cash-stack"></i> Cash Flow
                        </button>
                        <button type="button" onclick="switchGoalTab('appreciation')" id="tab-appreciation" class="goal-tab-btn px-3 py-2 rounded-lg text-xs font-semibold transition text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white flex items-center justify-center gap-1.5">
                            <i class="bi bi-graph-up-arrow"></i> Appreciation
                        </button>
                        <button type="button" onclick="switchGoalTab('tax_benefits')" id="tab-tax_benefits" class="goal-tab-btn px-3 py-2 rounded-lg text-xs font-semibold transition text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white flex items-center justify-center gap-1.5">
                            <i class="bi bi-shield-check"></i> Tax Shelter
                        </button>
                        <button type="button" onclick="switchGoalTab('diversification')" id="tab-diversification" class="goal-tab-btn px-3 py-2 rounded-lg text-xs font-semibold transition text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white flex items-center justify-center gap-1.5">
                            <i class="bi bi-globe2"></i> Diversification
                        </button>
                    </div>

                    <!-- TAB PANEL 1: Cash Flow -->
                    <div id="panel-cash_flow" class="goal-panel space-y-4">
                        <div class="p-4 rounded-xl bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900/30 flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-indigo-950 dark:text-indigo-200">Quarterly Cash Flow Objective</h3>
                                <p class="text-xs text-indigo-700 dark:text-indigo-400 mt-0.5">Optimized for consistent passive rental dividends distributed quarterly.</p>
                            </div>
                            <span class="badge-tw badge-success-tw text-xs font-bold">{{ $metrics->cash_flow_rating ?? 92 }}/100 Match</span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-[11px] font-semibold text-slate-400 uppercase block">Gross Rent</span>
                                <strong class="text-base font-bold text-slate-900 dark:text-white mt-1 block">
                                    ${{ number_format((float) ($metrics->gross_annual_rent ?? ($val * 0.112))) }}/yr
                                </strong>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-[11px] font-semibold text-slate-400 uppercase block">Operating Expenses</span>
                                <strong class="text-base font-bold text-slate-600 dark:text-slate-400 mt-1 block">
                                    ${{ number_format((float) ($metrics->operating_expenses ?? ($val * 0.112 * 0.23))) }}/yr
                                </strong>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-[11px] font-semibold text-slate-400 uppercase block">Net Operating Income</span>
                                <strong class="text-base font-bold text-indigo-600 dark:text-indigo-400 mt-1 block">
                                    ${{ number_format((float) ($metrics->net_operating_income ?? ($val * 0.112 * 0.77))) }}/yr
                                </strong>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-[11px] font-semibold text-slate-400 uppercase block">Net Cash Flow</span>
                                <strong class="text-base font-bold text-emerald-600 dark:text-emerald-400 mt-1 block">
                                    ${{ number_format((float) ($metrics->annual_cash_flow ?? ($val * 0.0825))) }}/yr
                                </strong>
                            </div>
                        </div>
                    </div>

                    <!-- TAB PANEL 2: Appreciation -->
                    <div id="panel-appreciation" class="goal-panel space-y-4 hidden">
                        <div class="p-4 rounded-xl bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900/30 flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-indigo-950 dark:text-indigo-200">Capital Growth & Compounding Equity</h3>
                                <p class="text-xs text-indigo-700 dark:text-indigo-400 mt-0.5">Captures long-term real estate equity appreciation upon institutional asset exit.</p>
                            </div>
                            <span class="badge-tw badge-info-tw text-xs font-bold">{{ $metrics->appreciation_rating ?? 88 }}/100 Match</span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-[11px] font-semibold text-slate-400 uppercase block">Current Valuation</span>
                                <strong class="text-base font-bold text-slate-900 dark:text-white mt-1 block">${{ number_format((float) $val) }}</strong>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-[11px] font-semibold text-slate-400 uppercase block">Annual Growth</span>
                                <strong class="text-base font-bold text-emerald-600 dark:text-emerald-400 mt-1 block">+{{ $metrics->estimated_appreciation_rate ?? 5.2 }}%/yr</strong>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-[11px] font-semibold text-slate-400 uppercase block">5-Yr Target Value</span>
                                <strong class="text-base font-bold text-indigo-600 dark:text-indigo-400 mt-1 block">
                                    ${{ number_format((float) ($metrics->projected_value_5yr ?? ($val * 1.288))) }}
                                </strong>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-[11px] font-semibold text-slate-400 uppercase block">Projected 5-Yr IRR</span>
                                <strong class="text-base font-bold text-emerald-600 dark:text-emerald-400 mt-1 block">{{ $metrics->projected_irr ?? 14.62 }}%</strong>
                            </div>
                        </div>
                    </div>

                    <!-- TAB PANEL 3: Tax Benefits -->
                    <div id="panel-tax_benefits" class="goal-panel space-y-4 hidden">
                        <div class="p-4 rounded-xl bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900/30 flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-indigo-950 dark:text-indigo-200">Tax Shielding & Section 1031 Rollover</h3>
                                <p class="text-xs text-indigo-700 dark:text-indigo-400 mt-0.5">Pass-through MACRS depreciation deductions and like-kind exchange qualification.</p>
                            </div>
                            <span class="badge-tw badge-success-tw text-xs font-bold">{{ $metrics->tax_benefit_rating ?? 94 }}/100 Match</span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-[11px] font-semibold text-slate-400 uppercase block">Annual Depreciation</span>
                                <strong class="text-base font-bold text-indigo-600 dark:text-indigo-400 mt-1 block">
                                    ${{ number_format((float) ($metrics->annual_depreciation_deduction ?? (($val * 0.85) / 27.5))) }}
                                </strong>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-[11px] font-semibold text-slate-400 uppercase block">Est. Tax Shield</span>
                                <strong class="text-base font-bold text-emerald-600 dark:text-emerald-400 mt-1 block">
                                    ${{ number_format((float) ($metrics->tax_savings_estimate ?? ((($val * 0.85) / 27.5) * 0.32))) }}/yr
                                </strong>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-[11px] font-semibold text-slate-400 uppercase block">1031 Exchange</span>
                                <strong class="text-base font-bold text-slate-900 dark:text-white mt-1 block">
                                    {{ ($metrics->is_1031_exchange_eligible ?? true) ? 'Qualified' : 'Standard' }}
                                </strong>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-[11px] font-semibold text-slate-400 uppercase block">Cost Segregation</span>
                                <strong class="text-base font-bold text-slate-900 dark:text-white mt-1 block">
                                    {{ ($metrics->cost_segregation_eligible ?? true) ? 'Eligible' : 'Standard' }}
                                </strong>
                            </div>
                        </div>
                    </div>

                    <!-- TAB PANEL 4: Diversification -->
                    <div id="panel-diversification" class="goal-panel space-y-4 hidden">
                        <div class="p-4 rounded-xl bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900/30 flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-indigo-950 dark:text-indigo-200">Portfolio Diversification & Inflation Hedge</h3>
                                <p class="text-xs text-indigo-700 dark:text-indigo-400 mt-0.5">Spread investment across resilient vacation destination markets.</p>
                            </div>
                            <span class="badge-tw badge-info-tw text-xs font-bold">{{ $metrics->diversification_rating ?? 90 }}/100 Match</span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-[11px] font-semibold text-slate-400 uppercase block">Market Rating</span>
                                <strong class="text-base font-bold text-indigo-600 dark:text-indigo-400 mt-1 block">{{ $metrics->diversification_score ?? '8.8' }} / 10</strong>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-[11px] font-semibold text-slate-400 uppercase block">Projected Occupancy</span>
                                <strong class="text-base font-bold text-slate-900 dark:text-white mt-1 block">{{ $metrics->occupancy_rate_projected ?? '86.5' }}%</strong>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-[11px] font-semibold text-slate-400 uppercase block">Asset Class</span>
                                <strong class="text-base font-bold text-slate-900 dark:text-white mt-1 block">Luxury Vacation STR</strong>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <span class="text-[11px] font-semibold text-slate-400 uppercase block">Inflation Hedge</span>
                                <strong class="text-base font-bold text-emerald-600 dark:text-emerald-400 mt-1 block">Strong</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description & Overview -->
                <div class="card-tw space-y-4">
                    <div class="card-header-tw">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="bi bi-house-door text-indigo-600 dark:text-indigo-400"></i> About {{ $propName }}
                        </h2>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        {{ $propDesc }}
                    </p>
                </div>

                <!-- Amenities Checklist -->
                <div class="card-tw space-y-4">
                    <div class="card-header-tw">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="bi bi-stars text-indigo-600 dark:text-indigo-400"></i> Featured Amenities
                        </h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 text-sm text-slate-700 dark:text-slate-300">
                        <div class="flex items-center gap-2"><i class="bi bi-check2-circle text-emerald-500 text-base"></i> Panoramic Mountain View</div>
                        <div class="flex items-center gap-2"><i class="bi bi-check2-circle text-emerald-500 text-base"></i> 8-Person Hot Tub</div>
                        <div class="flex items-center gap-2"><i class="bi bi-check2-circle text-emerald-500 text-base"></i> High-Speed Starlink WiFi</div>
                        <div class="flex items-center gap-2"><i class="bi bi-check2-circle text-emerald-500 text-base"></i> Stone Fireplace</div>
                        <div class="flex items-center gap-2"><i class="bi bi-check2-circle text-emerald-500 text-base"></i> Game Room & Billiards</div>
                        <div class="flex items-center gap-2"><i class="bi bi-check2-circle text-emerald-500 text-base"></i> Keyless Smart Lock</div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Property Info Card -->
            <div class="lg:col-span-4">
                <div class="card-tw sticky top-28 p-6 space-y-6 shadow-md">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="badge-tw badge-success-tw text-xs flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Available for Investment
                            </span>
                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">
                                {{ $metrics->cap_rate ?? '8.62' }}% Cap Rate
                            </span>
                        </div>
                        <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $propName }}</h2>
                        @if($featuredProperty && $featuredProperty->propertyAddress)
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1.5">
                                <i class="bi bi-geo-alt-fill text-rose-500"></i>
                                {{ $featuredProperty->propertyAddress->address ?? '' }},
                                {{ $featuredProperty->propertyAddress->city ?? '' }},
                                {{ $featuredProperty->propertyAddress->state ?? '' }}
                            </p>
                        @endif
                    </div>

                    <!-- Underwriting Summary Snapshot -->
                    <div class="p-4 rounded-xl bg-indigo-50/70 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/50 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-600 dark:text-slate-400">Asset Valuation:</span>
                            <strong class="text-slate-900 dark:text-white">${{ number_format((float) $val) }}</strong>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-600 dark:text-slate-400">Annual Net Cash Flow:</span>
                            <strong class="text-emerald-600 dark:text-emerald-400">${{ number_format((float) ($metrics->annual_cash_flow ?? ($val * 0.0825))) }}/yr</strong>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-600 dark:text-slate-400">Projected 5-Yr Growth:</span>
                            <strong class="text-indigo-600 dark:text-indigo-400">+{{ $metrics->estimated_appreciation_rate ?? 5.2 }}%/yr</strong>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-800 text-center">
                        <div>
                            <span class="text-[11px] font-semibold uppercase text-slate-400 dark:text-slate-500 block">Bedrooms</span>
                            <strong class="text-sm font-bold text-slate-900 dark:text-white mt-0.5 block">{{ $beds }} Beds</strong>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold uppercase text-slate-400 dark:text-slate-500 block">Bathrooms</span>
                            <strong class="text-sm font-bold text-slate-900 dark:text-white mt-0.5 block">{{ $baths }} Baths</strong>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold uppercase text-slate-400 dark:text-slate-500 block">Square Feet</span>
                            <strong class="text-sm font-bold text-slate-900 dark:text-white mt-0.5 block">{{ number_format($sqft) }} SqFt</strong>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold uppercase text-slate-400 dark:text-slate-500 block">Year Built</span>
                            <strong class="text-sm font-bold text-slate-900 dark:text-white mt-0.5 block">{{ $year }}</strong>
                        </div>
                    </div>

                    <a href="{{ url('register') }}" class="btn-primary-tw w-full py-3 text-base shadow-md text-center flex items-center justify-center gap-2">
                        <i class="bi bi-wallet2"></i> Invest in this Offering
                    </a>

                    <div class="text-center text-xs text-slate-400 dark:text-slate-500 flex items-center justify-center gap-1.5">
                        <i class="bi bi-lock-fill text-slate-400"></i> Institutional fractional ownership deed
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function switchGoalTab(tabKey) {
    document.querySelectorAll('.goal-tab-btn').forEach(btn => {
        btn.classList.remove('bg-white', 'dark:bg-slate-900', 'text-indigo-600', 'dark:text-indigo-400', 'shadow-sm', 'font-bold');
        btn.classList.add('text-slate-600', 'dark:text-slate-400', 'font-semibold');
    });

    const activeBtn = document.getElementById('tab-' + tabKey);
    if (activeBtn) {
        activeBtn.classList.add('bg-white', 'dark:bg-slate-900', 'text-indigo-600', 'dark:text-indigo-400', 'shadow-sm', 'font-bold');
        activeBtn.classList.remove('text-slate-600', 'dark:text-slate-400', 'font-semibold');
    }

    document.querySelectorAll('.goal-panel').forEach(panel => panel.classList.add('hidden'));
    const activePanel = document.getElementById('panel-' + tabKey);
    if (activePanel) {
        activePanel.classList.remove('hidden');
    }
}
</script>

@include('frontend.common.footer')