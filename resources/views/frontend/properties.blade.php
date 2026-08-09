@include('frontend.common.header', ['title' => 'Browse Properties | Gautam Real Estate'])

@php
    $propertyList = isset($properties) ? $properties : \App\Models\PropertyModel::with(['propertyImage', 'propertyAddress'])->latest()->paginate(9)->withQueryString();
@endphp

<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-10">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Available Properties</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Explore our curated selection of quality real estate listings.</p>
            </div>
            <div>
                <span class="badge-tw badge-info-tw px-3 py-1.5 text-xs">
                    <i class="bi bi-shield-check"></i> {{ number_format($propertyList->total()) }} Properties
                </span>
            </div>
        </div>

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
                @endphp
                <!-- Property Card -->
                <div class="card-tw p-0 overflow-hidden group hover:-translate-y-1 transition duration-300 flex flex-col justify-between">
                    <div>
                        <div class="relative h-60 overflow-hidden bg-slate-900">
                            <img src="{{ $imageSrc }}" alt="{{ $prop->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'800\' height=\'600\' viewBox=\'0 0 800 600\'><rect width=\'800\' height=\'600\' fill=\'%23312e81\'/><text x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-family=\'sans-serif\' font-size=\'24\' fill=\'%23ffffff\'>Luxury Real Estate</text></svg>';">
                            @if ($capRate)
                                <div class="absolute top-3 right-3 bg-indigo-900/90 backdrop-blur-md border border-indigo-500/30 text-white font-bold text-xs px-2.5 py-1 rounded-lg shadow-lg flex items-center gap-1.5">
                                    <i class="bi bi-graph-up-arrow text-emerald-400"></i>
                                    <span>{{ $capRate }}% Cap Rate</span>
                                </div>
                            @endif
                            @if ($metrics && $metrics->is_1031_exchange_eligible)
                                <div class="absolute bottom-3 left-3 bg-slate-950/80 backdrop-blur-md text-emerald-400 text-[11px] font-semibold px-2 py-0.5 rounded-md flex items-center gap-1">
                                    <i class="bi bi-shield-check"></i> 1031 Ready
                                </div>
                            @endif
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">{{ $prop->name }}</h2>
                                <div class="flex items-center gap-1.5 text-xs text-rose-600 dark:text-rose-400 font-medium mt-1">
                                    <i class="bi bi-geo-alt-fill"></i> {{ $city }}, {{ $state }}
                                </div>
                            </div>

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

                    <div class="p-6 pt-0">
                        <a href="{{ url('property_singlepage?id=' . $prop->id) }}" class="btn-primary-tw w-full py-2.5 text-center flex items-center justify-center gap-2">
                            <i class="bi bi-eye"></i> View Underwriting & Specs
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-400">
                    <i class="bi bi-building text-4xl block mb-2"></i> No active offerings available at this moment.
                </div>
            @endforelse
        </div>

        @if ($propertyList->hasPages())
            <div class="mt-12 pt-8 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-slate-500 dark:text-slate-400">
                    Showing <strong class="font-semibold text-slate-800 dark:text-slate-200">{{ $propertyList->firstItem() ?? 0 }}</strong> to <strong class="font-semibold text-slate-800 dark:text-slate-200">{{ $propertyList->lastItem() ?? 0 }}</strong> of <strong class="font-semibold text-slate-800 dark:text-slate-200">{{ number_format($propertyList->total()) }}</strong> investment offerings
                </div>
                <div>
                    {{ $propertyList->onEachSide(2)->links() }}
                </div>
            </div>
        @endif
    </div>
</section>

@include('frontend.common.footer')