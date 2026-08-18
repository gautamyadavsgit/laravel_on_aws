@include('frontend.common.header', ['title' => 'Investor Dashboard | Gautam Real Estate'])

<div class="py-10 lg:py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 dark:text-indigo-400">{{ __('dashboard.investor_portal') }}</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ __('dashboard.welcome_back') }}, {{ $user->first_name }}</h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('user.profile') }}" class="btn-secondary-tw px-4 py-2.5 text-sm font-semibold">
                    <i class="bi bi-person-gear"></i> {{ __('navigation.profile') }}
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <div class="card-tw p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs uppercase tracking-wider text-slate-400">{{ __('dashboard.interested_assets') }}</span>
                    <i class="bi bi-building text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div class="mt-4 text-3xl font-bold text-slate-900 dark:text-white">{{ $interests->count() }}</div>
            </div>

            <div class="card-tw p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs uppercase tracking-wider text-slate-400">{{ __('dashboard.favorite_properties') }}</span>
                    <i class="bi bi-heart-fill text-rose-600 dark:text-rose-400"></i>
                </div>
                <div class="mt-4 text-3xl font-bold text-slate-900 dark:text-white">{{ $favorites->count() }}</div>
            </div>

            <div class="card-tw p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs uppercase tracking-wider text-slate-400">{{ __('dashboard.verified') }}</span>
                    <i class="bi bi-shield-check text-emerald-600 dark:text-emerald-400"></i>
                </div>
                <div class="mt-4 text-3xl font-bold text-slate-900 dark:text-white">{{ $user->email_verified_at ? __('common.status_yes') : __('common.status_no') }}</div>
            </div>

            <div class="card-tw p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs uppercase tracking-wider text-slate-400">{{ __('dashboard.portfolio_status') }}</span>
                    <i class="bi bi-graph-up-arrow text-amber-600 dark:text-amber-400"></i>
                </div>
                <div class="mt-4 text-3xl font-bold text-slate-900 dark:text-white">{{ __('dashboard.active') }}</div>
            </div>
        </div>

        <div class="card-tw p-0 overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between gap-4">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-table text-indigo-600 dark:text-indigo-400"></i> {{ __('dashboard.my_investment_interest') }}
                </h2>
                <a href="{{ route('properties') }}" class="btn-primary-tw text-xs py-2 px-3">
                    <i class="bi bi-search"></i> {{ __('dashboard.browse_more_assets') }}
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-800/80 text-xs uppercase font-semibold text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="px-6 py-4">{{ __('dashboard.property_name') }}</th>
                            <th class="px-6 py-4">{{ __('dashboard.location') }}</th>
                            <th class="px-6 py-4">{{ __('dashboard.value') }}</th>
                            <th class="px-6 py-4">{{ __('dashboard.submitted') }}</th>
                            <th class="px-6 py-4">{{ __('dashboard.status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @forelse($interests as $interest)
                            @php $property = $interest->property; @endphp
                            <tr class="hover:bg-slate-50/90 dark:hover:bg-slate-800/50 transition">
                                <td class="px-6 py-5">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $property->name ?? 'Property' }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $property->management_company ?? 'Gautam REI' }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    @if($property && $property->propertyAddress)
                                        {{ $property->propertyAddress->city ?? '' }}{{ $property->propertyAddress->city && $property->propertyAddress->state ? ', ' : '' }}{{ $property->propertyAddress->state ?? '' }}
                                    @else
                                        <span class="text-slate-400 italic">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    @if($property && $property->propertyDetails)
                                        ${{ number_format((float) ($property->propertyDetails->value ?? 0)) }}
                                    @else
                                        <span class="text-slate-400 italic">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5">{{ $interest->created_at ? $interest->created_at->format('M d, Y') : 'N/A' }}</td>
                                <td class="px-6 py-5">
                                    <span class="badge-tw badge-success-tw text-xs py-1 px-2 uppercase">{{ $interest->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                    {{ __('dashboard.no_interests') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-tw p-0 overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between gap-4">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-heart-fill text-rose-600 dark:text-rose-400"></i> {{ __('dashboard.my_favorite_properties') }}
                </h2>
                <a href="{{ route('properties') }}" class="btn-primary-tw text-xs py-2 px-3">
                    <i class="bi bi-search"></i> {{ __('dashboard.browse_more_assets') }}
                </a>
            </div>

            @if($favorites->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-5">
                    @foreach($favorites as $favorite)
                        @php $property = $favorite->property; @endphp
                        <a href="{{ route('property.singlepage', ['id' => $property->id]) }}" class="group relative rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 hover:border-rose-300 dark:hover:border-rose-700 hover:shadow-lg transition">
                            <div class="aspect-video bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                @if($property && $property->propertyImage && count($property->propertyImage) > 0)
                                    <img src="{{ asset('storage/' . $property->propertyImage[0]->property_image_value) }}" alt="{{ $property->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                                        <i class="bi bi-image text-2xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4 bg-white dark:bg-slate-900">
                                <h3 class="font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">{{ $property->name ?? 'Property' }}</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    @if($property && $property->propertyAddress)
                                        {{ $property->propertyAddress->city ?? '' }}{{ $property->propertyAddress->city && $property->propertyAddress->state ? ', ' : '' }}{{ $property->propertyAddress->state ?? '' }}
                                    @else
                                        <span class="italic">{{ __('dashboard.location_na') }}</span>
                                    @endif
                                </p>
                                <div class="mt-3 flex items-center justify-between">
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">
                                        @if($property && $property->propertyDetails)
                                            ${{ number_format((float) ($property->propertyDetails->value ?? 0)) }}
                                        @else
                                            <span class="text-slate-400">N/A</span>
                                        @endif
                                    </span>
                                    <span class="badge-tw badge-success-tw text-[10px] py-1 px-2">{{ __('dashboard.favorited') }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center text-slate-500 dark:text-slate-400">
                    <i class="bi bi-heart text-4xl opacity-30 block mb-2"></i>
                    {{ __('dashboard.no_favorites') }}
                </div>
            @endif
        </div>
    </div>
</div>

@include('frontend.common.footer')
