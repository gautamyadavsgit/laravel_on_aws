@include('frontend.common.header', ['title' => $featuredProperty->name.' - Fractional Investment | Gautam Real Estate'])

@php
    $propName = $featuredProperty->name;
    $propDesc = $featuredProperty->description;
    $details = $featuredProperty->propertyDetails;
    $images = $featuredProperty->gallery_urls;
    $slideCount = count($images);
@endphp

<div class="py-10 lg:py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Navigation -->
        <div class="mb-6">
            <a href="{{ url('invest') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                <i class="bi bi-arrow-left"></i> {{ __('properties.view_all_investments') }}
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Column: Gallery, Specs, About -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Native Tailwind Image Carousel Slider -->
                <div id="propertySlider" class="relative rounded-2xl overflow-hidden bg-slate-900 shadow-lg group">
                    <div class="relative h-72 sm:h-96 md:h-[420px] w-full">
                        @forelse ($images as $idx => $imageUrl)
                            <div data-carousel-item class="absolute inset-0 transition-opacity duration-300 {{ $idx > 0 ? 'opacity-0 pointer-events-none hidden' : '' }}">
                                <img src="{{ $imageUrl }}" alt="{{ $propName }} image {{ $idx + 1 }}" class="w-full h-full object-cover">
                            </div>
                        @empty
                            <div class="absolute inset-0 flex items-center justify-center text-slate-500">
                                <i class="bi bi-image text-5xl"></i>
                            </div>
                        @endforelse
                    </div>

                    @if ($slideCount > 1)
                    <!-- Slide Navigation Controls -->
                    <button type="button" data-carousel-prev class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-slate-900/60 backdrop-blur-sm text-white flex items-center justify-center hover:bg-slate-900 transition" aria-label="Previous Slide">
                        <i class="bi bi-chevron-left text-lg"></i>
                    </button>
                    <button type="button" data-carousel-next class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-slate-900/60 backdrop-blur-sm text-white flex items-center justify-center hover:bg-slate-900 transition" aria-label="Next Slide">
                        <i class="bi bi-chevron-right text-lg"></i>
                    </button>

                    <!-- Indicators -->
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2">
                        @for ($i = 0; $i < $slideCount; $i++)
                            <button type="button" data-carousel-indicator class="h-2 rounded-full transition-all duration-300 {{ $i === 0 ? 'bg-white w-8' : 'bg-white/50 w-3' }}" aria-label="Slide {{ $i + 1 }}"></button>
                        @endfor
                    </div>
                    @endif
                </div>

                <!-- Basic Specs Bar -->
                <div class="card-tw p-5">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center divide-y sm:divide-y-0 sm:divide-x divide-slate-100 dark:divide-slate-800">
                        <div class="pt-2 sm:pt-0">
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">{{ __('properties.bedrooms') }}</span>
                            <strong class="text-lg font-bold text-slate-900 dark:text-white flex items-center justify-center gap-1.5 mt-0.5">
                                <i class="bi bi-door-closed text-indigo-600 dark:text-indigo-400"></i> {{ $details?->bedrooms ?? '—' }} {{ __('properties.beds') }}
                            </strong>
                        </div>
                        <div class="pt-2 sm:pt-0">
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">{{ __('properties.bathrooms') }}</span>
                            <strong class="text-lg font-bold text-slate-900 dark:text-white flex items-center justify-center gap-1.5 mt-0.5">
                                <i class="bi bi-droplet text-indigo-600 dark:text-indigo-400"></i> {{ $details?->baths ?? '—' }} {{ __('properties.baths') }}
                            </strong>
                        </div>
                        <div class="pt-2 sm:pt-0">
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">{{ __('properties.square_footage') }}</span>
                            <strong class="text-lg font-bold text-slate-900 dark:text-white flex items-center justify-center gap-1.5 mt-0.5">
                                <i class="bi bi-aspect-ratio text-indigo-600 dark:text-indigo-400"></i> {{ $details?->square_feets ? number_format($details->square_feets) : '—' }} {{ __('properties.sqft') }}
                            </strong>
                        </div>
                        <div class="pt-2 sm:pt-0">
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">{{ __('properties.year_built') }}</span>
                            <strong class="text-lg font-bold text-slate-900 dark:text-white flex items-center justify-center gap-1.5 mt-0.5">
                                <i class="bi bi-calendar3 text-indigo-600 dark:text-indigo-400"></i> {{ $details?->year_built ?? '—' }}
                            </strong>
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
            </div>

            <!-- Right Column: Property Info Card -->
            <div class="lg:col-span-4">
                <div class="card-tw sticky top-28 p-6 space-y-6 shadow-md">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="badge-tw badge-success-tw text-xs flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Available for Investment
                            </span>
                        </div>
                        <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $propName }}</h2>
                        @if($featuredProperty->propertyAddress)
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1.5">
                                <i class="bi bi-geo-alt-fill text-rose-500"></i>
                                {{ implode(', ', array_filter([$featuredProperty->propertyAddress->address, $featuredProperty->location_label])) }}
                            </p>
                        @endif
                    </div>

                    @if ($details?->value)
                        <div class="p-4 rounded-xl bg-indigo-50/70 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/50">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-600 dark:text-slate-400">Asset Valuation:</span>
                                <strong class="text-slate-900 dark:text-white">${{ number_format((float) $details->value) }}</strong>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-3 p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-800 text-center">
                        <div>
                            <span class="text-[11px] font-semibold uppercase text-slate-400 dark:text-slate-500 block">Bedrooms</span>
                            <strong class="text-sm font-bold text-slate-900 dark:text-white mt-0.5 block">{{ $details?->bedrooms ?? '—' }} Beds</strong>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold uppercase text-slate-400 dark:text-slate-500 block">Bathrooms</span>
                            <strong class="text-sm font-bold text-slate-900 dark:text-white mt-0.5 block">{{ $details?->baths ?? '—' }} Baths</strong>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold uppercase text-slate-400 dark:text-slate-500 block">Square Feet</span>
                            <strong class="text-sm font-bold text-slate-900 dark:text-white mt-0.5 block">{{ $details?->square_feets ? number_format($details->square_feets) : '—' }} SqFt</strong>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold uppercase text-slate-400 dark:text-slate-500 block">Year Built</span>
                            <strong class="text-sm font-bold text-slate-900 dark:text-white mt-0.5 block">{{ $details?->year_built ?? '—' }}</strong>
                        </div>
                    </div>

                    @if(Auth::check())
                        <form action="{{ route('investment.submit', ['propertyId' => $featuredProperty->id]) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="btn-primary-tw w-full py-3 text-base shadow-md text-center flex items-center justify-center gap-2">
                                <i class="bi bi-wallet2"></i> {{ __('properties.invest_offering') }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('register', ['redirect' => 'investment_interest']) }}" class="btn-primary-tw w-full py-3 text-base shadow-md text-center flex items-center justify-center gap-2">
                            <i class="bi bi-wallet2"></i> {{ __('properties.invest_offering') }}
                        </a>
                    @endif

                    @if(Auth::check())
                        <button type="button"
                            data-favorite-btn
                            data-property-id="{{ $featuredProperty->id }}"
                            class="favorite-btn w-full py-3 text-base text-center flex items-center justify-center gap-2 px-4 rounded-xl border transition {{ $isFavorited ? 'border-rose-300 dark:border-rose-800 bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400' : 'border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 hover:bg-rose-50 dark:hover:bg-rose-950/40' }}"
                            title="Add to favorites">
                            <i class="bi bi-heart{{ $isFavorited ? '-fill' : '' }} text-lg"></i> <span class="favorite-text">{{ $isFavorited ? 'Favorited' : 'Add to Favorites' }}</span>
                        </button>
                    @endif

                    <div class="text-center text-xs text-slate-400 dark:text-slate-500 flex items-center justify-center gap-1.5">
                        <i class="bi bi-lock-fill text-slate-400"></i> {{ __('properties.institutional_ownership') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('frontend.common.footer')
