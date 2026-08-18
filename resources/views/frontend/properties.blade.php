@include('frontend.common.header', ['title' => 'Browse Real Estate Investments | Gautam Real Estate'])

@php
    $propertyList = isset($properties) ? $properties : \App\Models\PropertyModel::with(['propertyImage', 'propertyAddress', 'propertyMetrics', 'propertyDetails'])->latest()->paginate(9)->withQueryString();
    $favoriteIds = isset($favoriteIds) ? $favoriteIds : (Auth::check() ? \App\Models\PropertyFavorite::where('user_id', Auth::id())->pluck('property_id')->toArray() : []);
    $appliedFilters = $filters ?? request()->all();
    $filterOpts = $filterOptions ?? [
        'cities' => ['Gatlinburg', 'Pigeon Forge', 'Sevierville', 'Nashville', 'Austin', 'Miami'],
        'states' => ['TN', 'FL', 'TX', 'NC', 'GA'],
        'types' => ['Luxury Cabin', 'Single Family', 'Multi-Family', 'Commercial', 'Short-Term Rental', 'Fractional Estate'],
    ];

    $hasActiveFilters = !empty($appliedFilters['q']) ||
        !empty($appliedFilters['location']) ||
        !empty($appliedFilters['min_price']) ||
        !empty($appliedFilters['max_price']) ||
        !empty($appliedFilters['property_type']) ||
        !empty($appliedFilters['bedrooms']) ||
        !empty($appliedFilters['bathrooms']) ||
        !empty($appliedFilters['min_cap_rate']) ||
        !empty($appliedFilters['is_1031']) ||
        (!empty($appliedFilters['sort_by']) && $appliedFilters['sort_by'] !== 'latest');
@endphp

<section class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Flash Alerts -->
        @if (session('success'))
            <div role="alert" class="alert-tw alert-success-tw">
                <i class="bi bi-check-circle-fill text-lg text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5"></i>
                <div class="flex-1 text-sm font-medium">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div role="alert" class="alert-tw alert-danger-tw">
                <i class="bi bi-exclamation-triangle-fill text-lg text-rose-600 dark:text-rose-400 shrink-0 mt-0.5"></i>
                <div class="flex-1 text-sm font-medium">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Unverified Email Notification Banner -->
        @auth
            @if (!Auth::user()->email_verified_at)
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 dark:bg-amber-950/40 dark:border-amber-800/70 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-amber-900 dark:text-amber-200 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg shrink-0">
                            <i class="bi bi-envelope-exclamation"></i>
                        </div>
                        <div>
                            <div class="text-sm font-bold">Please verify your email address ({{ Auth::user()->email }})</div>
                            <div class="text-xs text-amber-700 dark:text-amber-300">An activation link was sent to your inbox. Verify your account to finalize deed access.</div>
                        </div>
                    </div>
                    <form action="{{ route('verification.resend') }}" method="POST" class="shrink-0">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-600 hover:bg-amber-700 active:bg-amber-800 text-white text-xs font-semibold shadow-sm transition">
                            <i class="bi bi-arrow-clockwise"></i> Resend Email
                        </button>
                    </form>
                </div>
            @endif
        @endauth

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Institutional Opportunities</span>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white mt-1">Investment Offerings</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Discover vetted fractional real estate assets tailored to your yield & growth goals.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="badge-tw badge-info-tw px-3.5 py-1.5 text-xs font-semibold shadow-sm">
                    <i class="bi bi-shield-check"></i> {{ number_format($propertyList->total()) }} Offerings Available
                </span>
                @if($hasActiveFilters)
                    <a href="{{ route('properties') }}" class="text-xs text-rose-600 dark:text-rose-400 hover:underline font-semibold flex items-center gap-1">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                    </a>
                @endif
            </div>
        </div>

        <!-- Comprehensive Multi-Criteria Search & Filter System -->
        <div class="card-tw p-5 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200/80 dark:border-slate-800/80 shadow-md">
            <form action="{{ route('properties') }}" method="GET" id="propertiesFilterForm" class="space-y-4">
                <!-- Primary Search Bar Row -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                    <!-- Keyword search -->
                    <div class="md:col-span-4 relative">
                        <label for="searchQuery" class="sr-only">Search</label>
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-search text-sm"></i>
                        </div>
                        <input type="text"
                            name="q"
                            id="searchQuery"
                            value="{{ request('q') }}"
                            placeholder="Search by name, features, or keywords..."
                            class="input-tw pl-10 text-sm w-full">
                    </div>

                    <!-- Location search -->
                    <div class="md:col-span-3 relative">
                        <label for="locationQuery" class="sr-only">Location</label>
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-geo-alt-fill text-sm text-rose-500"></i>
                        </div>
                        <input type="text"
                            name="location"
                            id="locationQuery"
                            list="locationSuggestions"
                            value="{{ request('location') }}"
                            placeholder="City, State (e.g. Gatlinburg, TN)"
                            class="input-tw pl-10 text-sm w-full">
                        <datalist id="locationSuggestions">
                            @foreach($filterOpts['cities'] as $city)
                                <option value="{{ $city }}"></option>
                            @endforeach
                            @foreach($filterOpts['states'] as $state)
                                <option value="{{ $state }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Property Type -->
                    <div class="md:col-span-3">
                        <label for="propertyType" class="sr-only">Property Type</label>
                        <select name="property_type" id="propertyType" class="input-tw text-sm w-full">
                            <option value="">All Property Types</option>
                            @foreach($filterOpts['types'] as $type)
                                <option value="{{ $type }}" {{ request('property_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="md:col-span-2 flex items-center gap-2">
                        <button type="submit" class="btn-primary-tw flex-1 py-2.5 text-sm flex items-center justify-center gap-1.5 shadow-sm">
                            <i class="bi bi-funnel-fill"></i> Filter
                        </button>
                        <button type="button"
                            id="toggleAdvancedFiltersBtn"
                            class="p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition relative"
                            title="Toggle Advanced Filters"
                            aria-expanded="false">
                            <i class="bi bi-sliders text-base"></i>
                            @if(!empty(request('min_price')) || !empty(request('max_price')) || !empty(request('bedrooms')) || !empty(request('bathrooms')) || !empty(request('min_cap_rate')) || !empty(request('is_1031')) || (!empty(request('sort_by')) && request('sort_by') !== 'latest'))
                                <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-indigo-600 rounded-full ring-2 ring-white dark:ring-slate-900"></span>
                            @endif
                        </button>
                    </div>
                </div>

                <!-- Expandable / Collapsible Advanced Filters -->
                <div id="advancedFiltersSection" class="{{ (!empty(request('min_price')) || !empty(request('max_price')) || !empty(request('bedrooms')) || !empty(request('bathrooms')) || !empty(request('min_cap_rate')) || !empty(request('is_1031')) || (!empty(request('sort_by')) && request('sort_by') !== 'latest')) ? '' : 'hidden' }} pt-4 border-t border-slate-100 dark:border-slate-800/80">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3.5 text-xs">
                        <!-- Min Price -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Min Price ($)</label>
                            <input type="number"
                                name="min_price"
                                step="25000"
                                value="{{ request('min_price') }}"
                                placeholder="Min ($)"
                                class="input-tw text-xs w-full py-2">
                        </div>

                        <!-- Max Price -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Max Price ($)</label>
                            <input type="number"
                                name="max_price"
                                step="50000"
                                value="{{ request('max_price') }}"
                                placeholder="Max ($)"
                                class="input-tw text-xs w-full py-2">
                        </div>

                        <!-- Min Bedrooms -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Bedrooms</label>
                            <select name="bedrooms" class="input-tw text-xs w-full py-2">
                                <option value="">Any Beds</option>
                                <option value="1" {{ request('bedrooms') == '1' ? 'selected' : '' }}>1+ Bed</option>
                                <option value="2" {{ request('bedrooms') == '2' ? 'selected' : '' }}>2+ Beds</option>
                                <option value="3" {{ request('bedrooms') == '3' ? 'selected' : '' }}>3+ Beds</option>
                                <option value="4" {{ request('bedrooms') == '4' ? 'selected' : '' }}>4+ Beds</option>
                                <option value="5" {{ request('bedrooms') == '5' ? 'selected' : '' }}>5+ Beds</option>
                            </select>
                        </div>

                        <!-- Min Cap Rate -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Min Cap Rate (%)</label>
                            <select name="min_cap_rate" class="input-tw text-xs w-full py-2">
                                <option value="">Any Cap Rate</option>
                                <option value="6" {{ request('min_cap_rate') == '6' ? 'selected' : '' }}>6%+ Cap</option>
                                <option value="8" {{ request('min_cap_rate') == '8' ? 'selected' : '' }}>8%+ Cap</option>
                                <option value="10" {{ request('min_cap_rate') == '10' ? 'selected' : '' }}>10%+ Cap</option>
                                <option value="12" {{ request('min_cap_rate') == '12' ? 'selected' : '' }}>12%+ High Yield</option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Sort By</label>
                            <select name="sort_by" class="input-tw text-xs w-full py-2">
                                <option value="latest" {{ request('sort_by') === 'latest' ? 'selected' : '' }}>Newest Listed</option>
                                <option value="price_asc" {{ request('sort_by') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ request('sort_by') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="cap_rate_desc" {{ request('sort_by') === 'cap_rate_desc' ? 'selected' : '' }}>Highest Cap Rate</option>
                                <option value="cash_flow_desc" {{ request('sort_by') === 'cash_flow_desc' ? 'selected' : '' }}>Highest Cash Flow</option>
                            </select>
                        </div>

                        <!-- 1031 Exchange Toggle -->
                        <div class="flex items-center pt-5">
                            <label class="relative flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox"
                                    name="is_1031"
                                    value="1"
                                    {{ request('is_1031') ? 'checked' : '' }}
                                    class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900">
                                <span class="text-xs font-semibold text-slate-800 dark:text-slate-200 flex items-center gap-1">
                                    <i class="bi bi-shield-check text-emerald-500"></i> 1031 Ready
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Active Filter Tags Display -->
                @if($hasActiveFilters)
                    <div class="flex flex-wrap items-center gap-2 pt-2 text-xs">
                        <span class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider">Active Filters:</span>
                        
                        @if(!empty(request('q')))
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 font-medium">
                                Keyword: "{{ request('q') }}"
                                <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="hover:text-rose-600 ml-1"><i class="bi bi-x"></i></a>
                            </span>
                        @endif

                        @if(!empty(request('location')))
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 font-medium">
                                Location: {{ request('location') }}
                                <a href="{{ request()->fullUrlWithQuery(['location' => null]) }}" class="hover:text-rose-600 ml-1"><i class="bi bi-x"></i></a>
                            </span>
                        @endif

                        @if(!empty(request('property_type')))
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 font-medium">
                                Type: {{ request('property_type') }}
                                <a href="{{ request()->fullUrlWithQuery(['property_type' => null]) }}" class="hover:text-rose-600 ml-1"><i class="bi bi-x"></i></a>
                            </span>
                        @endif

                        @if(!empty(request('min_price')) || !empty(request('max_price')))
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 font-medium">
                                Price: ${{ number_format((float) request('min_price', 0)) }} - {{ !empty(request('max_price')) ? '$' . number_format((float) request('max_price')) : 'Any' }}
                                <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}" class="hover:text-rose-600 ml-1"><i class="bi bi-x"></i></a>
                            </span>
                        @endif

                        @if(!empty(request('bedrooms')))
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 font-medium">
                                {{ request('bedrooms') }}+ Beds
                                <a href="{{ request()->fullUrlWithQuery(['bedrooms' => null]) }}" class="hover:text-rose-600 ml-1"><i class="bi bi-x"></i></a>
                            </span>
                        @endif

                        @if(!empty(request('min_cap_rate')))
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 font-medium">
                                Cap Rate: {{ request('min_cap_rate') }}%+
                                <a href="{{ request()->fullUrlWithQuery(['min_cap_rate' => null]) }}" class="hover:text-rose-600 ml-1"><i class="bi bi-x"></i></a>
                            </span>
                        @endif

                        @if(!empty(request('is_1031')))
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 font-medium">
                                1031 Exchange Ready
                                <a href="{{ request()->fullUrlWithQuery(['is_1031' => null]) }}" class="hover:text-rose-600 ml-1"><i class="bi bi-x"></i></a>
                            </span>
                        @endif

                        <a href="{{ route('properties') }}" class="text-xs text-rose-600 dark:text-rose-400 hover:underline font-semibold ml-2">Clear All</a>
                    </div>
                @endif
            </form>
        </div>

        <!-- Personalized Recommendations / Curated For You Strip -->
        @if(isset($recommendations) && $recommendations->count() > 0 && !$hasActiveFilters)
            <div class="p-5 rounded-2xl bg-gradient-to-r from-indigo-900/10 via-purple-900/10 to-indigo-900/10 dark:from-indigo-950/40 dark:via-purple-950/40 dark:to-indigo-950/40 border border-indigo-200/80 dark:border-indigo-800/80 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-sm shadow-md shadow-indigo-500/20">
                            <i class="bi bi-stars"></i>
                        </span>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white">Recommended For You</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Curated opportunities matched to your investment criteria & preference history</p>
                        </div>
                    </div>
                    <span class="badge-tw badge-info-tw text-[11px] py-1 px-2.5 hidden sm:inline-flex">
                        <i class="bi bi-cpu"></i> AI Match Score: 96%
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($recommendations as $rec)
                        @php
                            $recImage = $rec->propertyImage ? $rec->propertyImage->firstWhere('property_image_key', 'property_image') : null;
                            $recImgSrc = $recImage ? asset('storage/' . $recImage->property_image_value) : asset('storage/property_images/property_1.png');
                            $recCap = $rec->propertyMetrics->cap_rate ?? null;
                            $recCity = $rec->propertyAddress->city ?? 'Gatlinburg';
                            $recState = $rec->propertyAddress->state ?? 'TN';
                            $isRecFavorited = in_array($rec->id, $favoriteIds);
                        @endphp
                        <div class="relative group rounded-xl overflow-hidden bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-indigo-400 dark:hover:border-indigo-600 transition shadow-sm flex flex-col justify-between">
                            <div class="relative h-36 overflow-hidden bg-slate-900">
                                <img src="{{ $recImgSrc }}" alt="{{ $rec->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'600\' height=\'400\' viewBox=\'0 0 600 400\'><rect width=\'600\' height=\'400\' fill=\'%23312e81\'/><text x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-family=\'sans-serif\' font-size=\'20\' fill=\'%23ffffff\'>Top Yield Asset</text></svg>';">
                                
                                <button type="button"
                                    data-favorite-btn
                                    data-property-id="{{ $rec->id }}"
                                    class="favorite-card-btn absolute top-2.5 left-2.5 z-10 w-8 h-8 rounded-full flex items-center justify-center transition shadow-md backdrop-blur-md {{ $isRecFavorited ? 'bg-rose-500/90 text-white hover:bg-rose-600' : 'bg-slate-900/60 text-white hover:bg-slate-900/90 hover:text-rose-400' }}"
                                    title="{{ $isRecFavorited ? 'Remove from Favorites' : 'Add to Favorites' }}"
                                    aria-label="Favorite">
                                    <i class="bi bi-heart{{ $isRecFavorited ? '-fill' : '' }} text-xs pointer-events-none"></i>
                                </button>

                                @if($recCap)
                                    <span class="absolute top-2.5 right-2.5 bg-indigo-900/90 backdrop-blur-md text-white font-bold text-[10px] px-2 py-0.5 rounded shadow">
                                        {{ $recCap }}% Cap
                                    </span>
                                @endif
                            </div>
                            <div class="p-3.5 space-y-2">
                                <div>
                                    <h3 class="font-bold text-sm text-slate-900 dark:text-white truncate">{{ $rec->name }}</h3>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1 mt-0.5">
                                        <i class="bi bi-geo-alt-fill text-rose-500"></i> {{ $recCity }}, {{ $recState }}
                                    </div>
                                </div>
                                <a href="{{ route('property.singlepage', ['slug' => $rec->slug ?? $rec->id]) }}" class="btn-primary-tw w-full py-1.5 text-xs text-center flex items-center justify-center gap-1.5">
                                    <i class="bi bi-eye"></i> View Analysis
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Main Property Listings Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($propertyList as $prop)
                @php
                    $heroImage = $prop->propertyImage ? $prop->propertyImage->firstWhere('property_image_key', 'property_image') : null;
                    $imageSrc = $heroImage ? asset('storage/' . $heroImage->property_image_value) : asset('storage/property_images/property_1.png');
                    $city = $prop->propertyAddress->city ?? 'Gatlinburg';
                    $state = $prop->propertyAddress->state ?? 'TN';
                    $metrics = $prop->propertyMetrics;
                    $capRate = $metrics->cap_rate ?? null;
                    $cashFlow = $metrics->annual_cash_flow ?? null;
                    $growth5yr = $metrics->estimated_appreciation_rate ?? 5.2;
                    $isFavorited = in_array($prop->id, $favoriteIds);
                    $propType = $prop->propertyDetails->type ?? 'Fractional Estate';
                    $propBeds = $prop->propertyDetails->bedrooms ?? null;
                    $propBaths = $prop->propertyDetails->baths ?? null;
                    $propSqft = $prop->propertyDetails->square_feets ?? null;
                    $propPrice = $prop->propertyDetails->value ?? null;
                @endphp
                <!-- Property Card -->
                <div class="card-tw p-0 overflow-hidden group hover:-translate-y-1 transition duration-300 flex flex-col justify-between">
                    <div>
                        <div class="relative h-64 overflow-hidden bg-slate-900">
                            <img src="{{ $imageSrc }}" alt="{{ $prop->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'800\' height=\'600\' viewBox=\'0 0 800 600\'><rect width=\'800\' height=\'600\' fill=\'%23312e81\'/><text x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-family=\'sans-serif\' font-size=\'24\' fill=\'%23ffffff\'>Luxury Real Estate</text></svg>';">
                            
                            <!-- Favorite Floating Action Button -->
                            <button type="button"
                                data-favorite-btn
                                data-property-id="{{ $prop->id }}"
                                class="favorite-card-btn absolute top-3 left-3 z-10 w-9 h-9 rounded-full flex items-center justify-center transition shadow-md backdrop-blur-md {{ $isFavorited ? 'bg-rose-500/90 text-white hover:bg-rose-600' : 'bg-slate-900/60 text-white hover:bg-slate-900/90 hover:text-rose-400' }}"
                                title="{{ $isFavorited ? __('properties.remove_favorites') : __('properties.add_to_favorites') }}"
                                aria-label="Favorite">
                                <i class="bi bi-heart{{ $isFavorited ? '-fill' : '' }} text-sm pointer-events-none"></i>
                            </button>

                            <!-- Cap Rate Badge -->
                            @if ($capRate)
                                <div class="absolute top-3 right-3 bg-indigo-900/90 backdrop-blur-md border border-indigo-500/30 text-white font-bold text-xs px-2.5 py-1 rounded-lg shadow-lg flex items-center gap-1.5">
                                    <i class="bi bi-graph-up-arrow text-emerald-400"></i>
                                    <span>{{ $capRate }}% Cap Rate</span>
                                </div>
                            @endif

                            <!-- Type & 1031 Status Tag -->
                            <div class="absolute bottom-3 left-3 flex items-center gap-1.5">
                                <span class="bg-slate-950/80 backdrop-blur-md text-slate-200 text-[11px] font-medium px-2 py-0.5 rounded-md">
                                    {{ $propType }}
                                </span>
                                @if ($metrics && $metrics->is_1031_exchange_eligible)
                                    <span class="bg-slate-950/80 backdrop-blur-md text-emerald-400 text-[11px] font-semibold px-2 py-0.5 rounded-md flex items-center gap-1">
                                        <i class="bi bi-shield-check"></i> 1031
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">{{ $prop->name }}</h2>
                                <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    <span class="flex items-center gap-1 text-rose-600 dark:text-rose-400 font-medium">
                                        <i class="bi bi-geo-alt-fill"></i> {{ $city }}, {{ $state }}
                                    </span>
                                    @if($propPrice)
                                        <span class="font-bold text-slate-900 dark:text-white text-sm">
                                            ${{ number_format((float) $propPrice) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Specs Preview -->
                            @if($propBeds || $propBaths || $propSqft)
                                <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 py-1.5 border-y border-slate-100 dark:border-slate-800/80">
                                    @if($propBeds)
                                        <span class="flex items-center gap-1"><i class="bi bi-door-closed text-indigo-500"></i> {{ $propBeds }} Beds</span>
                                    @endif
                                    @if($propBaths)
                                        <span class="flex items-center gap-1"><i class="bi bi-droplet text-indigo-500"></i> {{ $propBaths }} Baths</span>
                                    @endif
                                    @if($propSqft)
                                        <span class="flex items-center gap-1"><i class="bi bi-aspect-ratio text-indigo-500"></i> {{ number_format($propSqft) }} SqFt</span>
                                    @endif
                                </div>
                            @endif

                            <!-- Financial Metrics Grid Preview -->
                            <div class="grid grid-cols-2 gap-2.5 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 text-xs">
                                <div>
                                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Net Cash Flow</span>
                                    <strong class="text-slate-900 dark:text-white font-bold">{{ $cashFlow ? '$' . number_format((float) $cashFlow) . '/yr' : 'Underwritten' }}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Est. Growth</span>
                                    <strong class="text-emerald-600 dark:text-emerald-400 font-bold">+{{ $growth5yr }}%/yr</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="p-6 pt-0 flex items-center gap-2">
                        <a href="{{ route('property.singlepage', ['slug' => $prop->slug ?? $prop->id]) }}" class="btn-primary-tw flex-1 py-2.5 text-center flex items-center justify-center gap-2">
                            <i class="bi bi-eye"></i> View Underwriting & Specs
                        </a>
                        <button type="button"
                            data-favorite-btn
                            data-property-id="{{ $prop->id }}"
                            class="favorite-icon-btn shrink-0 w-11 h-11 rounded-xl border flex items-center justify-center transition {{ $isFavorited ? 'border-rose-300 dark:border-rose-800 bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400' : 'border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-rose-600 hover:border-rose-300 dark:hover:border-rose-800 hover:bg-rose-50/50 dark:hover:bg-rose-950/20' }}"
                            title="{{ $isFavorited ? __('properties.remove_favorites') : __('properties.add_to_favorites') }}"
                            aria-label="Favorite">
                            <i class="bi bi-heart{{ $isFavorited ? '-fill' : '' }} text-lg pointer-events-none"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-16 px-4 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-800 space-y-4">
                    <div class="w-16 h-16 mx-auto rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 text-2xl">
                        <i class="bi bi-search"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">No Properties Matched Your Filter</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 max-w-md mx-auto mt-1">We couldn't find any real estate offerings matching your current search parameters.</p>
                    </div>
                    <div>
                        <a href="{{ route('properties') }}" class="btn-primary-tw inline-flex items-center gap-2 py-2 px-4 text-xs">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset All Filters
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($propertyList->hasPages())
            <div class="mt-12 pt-8 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-slate-500 dark:text-slate-400">
                    Showing <strong class="font-semibold text-slate-800 dark:text-slate-200">{{ $propertyList->firstItem() ?? 0 }}</strong> to <strong class="font-semibold text-slate-800 dark:text-slate-200">{{ $propertyList->lastItem() ?? 0 }}</strong> of <strong class="font-semibold text-slate-800 dark:text-slate-200">{{ number_format($propertyList->total()) }}</strong> investment offerings
                </div>
                <div>
                    {{ $propertyList->links() }}
                </div>
            </div>
        @endif
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleAdvancedFiltersBtn');
    const advancedSection = document.getElementById('advancedFiltersSection');

    if (toggleBtn && advancedSection) {
        toggleBtn.addEventListener('click', function() {
            const isHidden = advancedSection.classList.toggle('hidden');
            toggleBtn.setAttribute('aria-expanded', !isHidden);
        });
    }
});
</script>

@include('frontend.common.footer')