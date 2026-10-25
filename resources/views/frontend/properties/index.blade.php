@include('frontend.common.header', ['title' => 'Browse Real Estate Investments | Gautam Real Estate'])

@php
    $propertyList = $properties;
    $appliedFilters = $filters;
    $filterOpts = $filterOptions;

    $hasActiveFilters = !empty($appliedFilters['q']) ||
        !empty($appliedFilters['location']) ||
        !empty($appliedFilters['min_price']) ||
        !empty($appliedFilters['max_price']) ||
        !empty($appliedFilters['property_type']) ||
        !empty($appliedFilters['bedrooms']) ||
        !empty($appliedFilters['bathrooms']) ||
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
                    <a href="{{ route('properties.index') }}" class="text-xs text-rose-600 dark:text-rose-400 hover:underline font-semibold flex items-center gap-1">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                    </a>
                @endif
            </div>
        </div>

        <!-- Comprehensive Multi-Criteria Search & Filter System -->
        <div class="card-tw p-5 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200/80 dark:border-slate-800/80 shadow-md">
            <form action="{{ route('properties.index') }}" method="GET" id="propertiesFilterForm" class="space-y-4">
                <!-- Primary Search Bar Row -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                    <!-- Keyword search -->
                    <div class="md:col-span-4 relative">
                        <label for="searchQuery" class="sr-only">Search</label>
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-search text-sm"></i>
                        </div>
                        <input type="text"
                            name="q" maxlength="100"
                            id="searchQuery"
                            value="{{ ($filters['q'] ?? null) }}"
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
                            name="location" maxlength="100"
                            id="locationQuery"
                            list="locationSuggestions"
                            value="{{ ($filters['location'] ?? null) }}"
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
                                <option value="{{ $type }}" {{ ($filters['property_type'] ?? null) === $type ? 'selected' : '' }}>{{ $type }}</option>
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
                            @if(!empty(($filters['min_price'] ?? null)) || !empty(($filters['max_price'] ?? null)) || !empty(($filters['bedrooms'] ?? null)) || !empty(($filters['bathrooms'] ?? null)) || (!empty(($filters['sort_by'] ?? null)) && ($filters['sort_by'] ?? null) !== 'latest'))
                                <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-indigo-600 rounded-full ring-2 ring-white dark:ring-slate-900"></span>
                            @endif
                        </button>
                    </div>
                </div>

                <!-- Expandable / Collapsible Advanced Filters -->
                <div id="advancedFiltersSection" class="{{ (!empty(($filters['min_price'] ?? null)) || !empty(($filters['max_price'] ?? null)) || !empty(($filters['bedrooms'] ?? null)) || !empty(($filters['bathrooms'] ?? null)) || (!empty(($filters['sort_by'] ?? null)) && ($filters['sort_by'] ?? null) !== 'latest')) ? '' : 'hidden' }} pt-4 border-t border-slate-100 dark:border-slate-800/80">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3.5 text-xs">
                        <!-- Min Price -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Min Price ($)</label>
                            <input type="number"
                                name="min_price" min="0" max="100000000000"
                                step="25000"
                                value="{{ ($filters['min_price'] ?? null) }}"
                                placeholder="Min ($)"
                                class="input-tw text-xs w-full py-2">
                        </div>

                        <!-- Max Price -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Max Price ($)</label>
                            <input type="number"
                                name="max_price" min="0" max="100000000000"
                                step="50000"
                                value="{{ ($filters['max_price'] ?? null) }}"
                                placeholder="Max ($)"
                                class="input-tw text-xs w-full py-2">
                        </div>

                        <!-- Min Bedrooms -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Bedrooms</label>
                            <select name="bedrooms" class="input-tw text-xs w-full py-2">
                                <option value="">Any Beds</option>
                                <option value="1" {{ ($filters['bedrooms'] ?? null) == '1' ? 'selected' : '' }}>1+ Bed</option>
                                <option value="2" {{ ($filters['bedrooms'] ?? null) == '2' ? 'selected' : '' }}>2+ Beds</option>
                                <option value="3" {{ ($filters['bedrooms'] ?? null) == '3' ? 'selected' : '' }}>3+ Beds</option>
                                <option value="4" {{ ($filters['bedrooms'] ?? null) == '4' ? 'selected' : '' }}>4+ Beds</option>
                                <option value="5" {{ ($filters['bedrooms'] ?? null) == '5' ? 'selected' : '' }}>5+ Beds</option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Sort By</label>
                            <select name="sort_by" class="input-tw text-xs w-full py-2">
                                <option value="latest" {{ ($filters['sort_by'] ?? null) === 'latest' ? 'selected' : '' }}>Newest Listed</option>
                                <option value="price_asc" {{ ($filters['sort_by'] ?? null) === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ ($filters['sort_by'] ?? null) === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Active Filter Tags Display -->
                @if($hasActiveFilters)
                    <div class="flex flex-wrap items-center gap-2 pt-2 text-xs">
                        <span class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider">Active Filters:</span>

                        @if(!empty(($filters['q'] ?? null)))
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 font-medium">
                                Keyword: "{{ ($filters['q'] ?? null) }}"
                                <a href="{{ route('properties.index', \Illuminate\Support\Arr::except($filters, ['q'])) }}" class="hover:text-rose-600 ml-1"><i class="bi bi-x"></i></a>
                            </span>
                        @endif

                        @if(!empty(($filters['location'] ?? null)))
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 font-medium">
                                Location: {{ ($filters['location'] ?? null) }}
                                <a href="{{ route('properties.index', \Illuminate\Support\Arr::except($filters, ['location'])) }}" class="hover:text-rose-600 ml-1"><i class="bi bi-x"></i></a>
                            </span>
                        @endif

                        @if(!empty(($filters['property_type'] ?? null)))
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 font-medium">
                                Type: {{ ($filters['property_type'] ?? null) }}
                                <a href="{{ route('properties.index', \Illuminate\Support\Arr::except($filters, ['property_type'])) }}" class="hover:text-rose-600 ml-1"><i class="bi bi-x"></i></a>
                            </span>
                        @endif

                        @if(!empty(($filters['min_price'] ?? null)) || !empty(($filters['max_price'] ?? null)))
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 font-medium">
                                Price: ${{ number_format((float) ($filters['min_price'] ?? 0)) }} - {{ !empty(($filters['max_price'] ?? null)) ? '$' . number_format((float) ($filters['max_price'] ?? null)) : 'Any' }}
                                <a href="{{ route('properties.index', \Illuminate\Support\Arr::except($filters, ['min_price', 'max_price'])) }}" class="hover:text-rose-600 ml-1"><i class="bi bi-x"></i></a>
                            </span>
                        @endif

                        @if(!empty(($filters['bedrooms'] ?? null)))
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 font-medium">
                                {{ ($filters['bedrooms'] ?? null) }}+ Beds
                                <a href="{{ route('properties.index', \Illuminate\Support\Arr::except($filters, ['bedrooms'])) }}" class="hover:text-rose-600 ml-1"><i class="bi bi-x"></i></a>
                            </span>
                        @endif

                        <a href="{{ route('properties.index') }}" class="text-xs text-rose-600 dark:text-rose-400 hover:underline font-semibold ml-2">Clear All</a>
                    </div>
                @endif
            </form>
        </div>

        <!-- Main Property Listings Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($propertyList as $prop)
                @php
                    $isFavorited = in_array($prop->id, $favoriteIds);
                    $propType = $prop->type;
                    $propBeds = $prop->bedrooms;
                    $propBaths = $prop->baths;
                    $propSqft = $prop->square_feets;
                    $propPrice = $prop->value;
                @endphp
                <!-- Property Card -->
                <div class="card-tw p-0 overflow-hidden group hover:-translate-y-1 transition duration-300 flex flex-col justify-between">
                    <div>
                        <div class="relative h-64 overflow-hidden bg-slate-900">
                            @if ($prop->hero_image_url)
                                <img src="{{ $prop->hero_image_url }}" alt="{{ $prop->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-500"><i class="bi bi-image text-4xl"></i></div>
                            @endif

                            <!-- Favorite Floating Action Button -->
                            <button type="button"
                                data-favorite-btn
                                data-property-id="{{ $prop->id }}"
                                class="favorite-card-btn absolute top-3 left-3 z-10 w-9 h-9 rounded-full flex items-center justify-center transition shadow-md backdrop-blur-md {{ $isFavorited ? 'bg-rose-500/90 text-white hover:bg-rose-600' : 'bg-slate-900/60 text-white hover:bg-slate-900/90 hover:text-rose-400' }}"
                                title="{{ $isFavorited ? __('properties.remove_favorites') : __('properties.add_to_favorites') }}"
                                aria-label="Favorite">
                                <i class="bi bi-heart{{ $isFavorited ? '-fill' : '' }} text-sm pointer-events-none"></i>
                            </button>

                            <!-- Type Tag -->
                            @if ($propType)
                                <div class="absolute bottom-3 left-3 flex items-center gap-1.5">
                                    <span class="bg-slate-950/80 backdrop-blur-md text-slate-200 text-[11px] font-medium px-2 py-0.5 rounded-md">
                                        {{ $propType }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="p-6 space-y-4">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">{{ $prop->name }}</h2>
                                <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    <span class="flex items-center gap-1 text-rose-600 dark:text-rose-400 font-medium">
                                        <i class="bi bi-geo-alt-fill"></i> {{ $prop->location_label }}
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
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="p-6 pt-0 flex items-center gap-2">
                        <a href="{{ route('properties.show', ['slug' => $prop->slug ?? $prop->id]) }}" class="btn-primary-tw flex-1 py-2.5 text-center flex items-center justify-center gap-2">
                            <i class="bi bi-eye"></i> View Details
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
                        <a href="{{ route('properties.index') }}" class="btn-primary-tw inline-flex items-center gap-2 py-2 px-4 text-xs">
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
