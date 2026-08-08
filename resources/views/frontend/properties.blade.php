@include('frontend.common.header', ['title' => 'Browse Investment Properties | Gautam Real Estate'])

@php
    $propertyList = isset($properties) ? $properties : \App\Models\PropertyModel::with(['propertyImage', 'propertyAddress', 'propertyAacf', 'propertyShare'])->latest()->paginate(9)->withQueryString();
@endphp

<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-10">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Active Fractional Offerings</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Explore vetted real estate investment opportunities with live capital raises.</p>
            </div>
            <div>
                <span class="badge-tw badge-info-tw px-3 py-1.5 text-xs">
                    <i class="bi bi-shield-check"></i> {{ number_format($propertyList->total()) }} Vetted Properties
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($propertyList as $prop)
                @php
                    $heroImage = $prop->propertyImage ? $prop->propertyImage->firstWhere('property_image_key', 'property_image') : null;
                    $imageSrc = $heroImage ? asset('storage/' . $heroImage->property_image_value) : asset('storage/property_images/property_1.png');
                    $raise = $prop->propertyShare->equity_raise ?? 617000;
                    $yield = $prop->propertyAacf->annual_rent_gross_yield ?? 10.4;
                    $city = $prop->propertyAddress->city ?? 'Gatlinburg';
                    $state = $prop->propertyAddress->state ?? 'TN';
                    $investors = isset($prop->propertyShare->total_developer_share_deeds) && $prop->propertyShare->total_developer_share_deeds > 0 ? (int)($prop->propertyShare->total_raise_share_deeds / 30) : 365;
                @endphp
                <!-- Property Card -->
                <div class="card-tw p-0 overflow-hidden group hover:-translate-y-1 transition duration-300">
                    <div class="relative h-60 overflow-hidden bg-slate-900">
                        <img src="{{ $imageSrc }}" alt="{{ $prop->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'800\' height=\'600\' viewBox=\'0 0 800 600\'><rect width=\'800\' height=\'600\' fill=\'%23312e81\'/><text x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-family=\'sans-serif\' font-size=\'24\' fill=\'%23ffffff\'>Luxury Real Estate</text></svg>';">
                        <span class="absolute top-3 right-3 badge-tw badge-info-tw font-bold shadow-sm">{{ $prop->availability }}</span>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ $prop->name }}</h2>
                            <div class="flex items-center gap-1.5 text-xs text-rose-600 dark:text-rose-400 font-medium mt-1">
                                <i class="bi bi-geo-alt-fill"></i> {{ $city }}, {{ $state }}
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2 py-3 border-y border-slate-100 dark:border-slate-800 text-xs text-slate-500 dark:text-slate-400 text-center">
                            <div>
                                <span>Target Raise</span>
                                <strong class="block text-slate-900 dark:text-white font-bold mt-0.5">${{ number_format($raise) }}</strong>
                            </div>
                            <div>
                                <span>Investors</span>
                                <strong class="block text-slate-900 dark:text-white font-bold mt-0.5">{{ $investors }}</strong>
                            </div>
                            <div>
                                <span>Proj. Yield</span>
                                <strong class="block text-emerald-600 dark:text-emerald-400 font-bold mt-0.5">{{ $yield }}%</strong>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <div class="flex justify-between text-xs font-semibold text-slate-700 dark:text-slate-300">
                                <span>Funding Progress</span>
                                <span class="text-indigo-600 dark:text-indigo-400">{{ $prop->availability }}</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: 88%;"></div>
                            </div>
                        </div>

                        <a href="{{ url('property_singlepage?id=' . $prop->id) }}" class="btn-primary-tw w-full py-2.5">
                            <i class="bi bi-eye"></i> View Offering Details
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
                    {{ $propertyList->links() }}
                </div>
            </div>
        @endif
    </div>
</section>

@include('frontend.common.footer')