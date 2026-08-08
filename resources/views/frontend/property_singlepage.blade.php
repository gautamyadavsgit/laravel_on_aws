@include('frontend.common.header', ['title' => 'Smoky Mountain Luxury Estate - Fractional Investment | Gautam Real Estate'])

@php
    $featuredProperty = \App\Models\PropertyModel::with(['propertyImage', 'propertyFloorplan', 'propertyDocumentModel', 'propertyAddress', 'propertyDetails', 'propertyAmenities'])->first();
    $propName = $featuredProperty->name ?? 'Smoky Mountain Luxury Cabin';
    $propDesc = $featuredProperty->description ?? 'Located in the high-demand vacation corridor of Gatlinburg, TN, this luxury short-term rental property features custom timber architecture, panoramic Great Smoky Mountain vistas, private hot tub deck, and dedicated entertainment suites.';
    $details = $featuredProperty->propertyDetails ?? null;
    $beds = $details->bedrooms ?? 4;
    $baths = $details->baths ?? 3.5;
    $sqft = $details->square_feets ?? 2850;
    $year = $details->year_built ?? 2022;
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
            <!-- Left Column: Gallery, Specs, About, Financials -->
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

                    <a href="{{ url('register') }}" class="btn-primary-tw w-full py-3 text-base shadow-md">
                        <i class="bi bi-envelope-check"></i> Enquire About This Property
                    </a>

                    <div class="text-center text-xs text-slate-400 dark:text-slate-500 flex items-center justify-center gap-1.5">
                        <i class="bi bi-lock-fill text-slate-400"></i> Your information is kept private
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('frontend.common.footer')