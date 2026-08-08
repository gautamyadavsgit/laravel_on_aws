@extends('admin.common.page')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-600/10 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl font-bold">
                    <i class="bi bi-building"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Property Portfolio Management
                    </h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Manage all real estate assets, fractional capital offerings, specifications, and legal documentation.
                    </p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('manage-property.create') }}" class="btn-primary-tw shadow-sm py-2.5 px-4 text-sm font-semibold flex items-center gap-2">
                <i class="bi bi-plus-lg text-base"></i> Add New Property
            </a>
        </div>
    </div>

    <!-- Properties Master-Detail Table Card -->
    <div class="card-tw p-0 overflow-hidden shadow-sm">
        <!-- Toolbar & Filter Header -->
        <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-900/50">
            <div class="flex items-center gap-3 flex-1 max-w-lg">
                <div class="relative w-full">
                    <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="propertyTableSearch" onkeyup="filterPropertyTable()" placeholder="Search properties by name, ID, city, or management company..." class="input-tw pl-9 py-2.5 text-sm">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span class="badge-tw badge-info-tw py-1.5 px-3 text-xs font-semibold whitespace-nowrap">
                    <i class="bi bi-layers-fill"></i> {{ number_format($property->total()) }} Properties Listed
                </span>
                <span class="text-xs text-slate-500 dark:text-slate-400 hidden sm:inline bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-3 py-1.5 rounded-lg font-medium">
                    Click "Expand Details" to view all sub-modules
                </span>
            </div>
        </div>

        <!-- Clean, Spacious Master Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300" id="propertyTable">
                <thead class="bg-slate-50 dark:bg-slate-800/80 text-xs uppercase font-semibold text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th scope="col" class="px-6 py-4 w-20 text-center">ID</th>
                        <th scope="col" class="px-6 py-4 min-w-[320px]">Property Asset</th>
                        <th scope="col" class="px-6 py-4 min-w-[160px]">Status</th>
                        <th scope="col" class="px-6 py-4 min-w-[200px]">Location</th>
                        <th scope="col" class="px-6 py-4 min-w-[160px]">Valuation</th>
                        <th scope="col" class="px-6 py-4 min-w-[260px] text-right pr-8">Actions & Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse ($property as $prop)
                        @php
                            $thumb = $prop->propertyImage->first();
                            $addr = $prop->propertyAddress;
                            $details = $prop->propertyDetails;
                            $offering = $prop->propertyOffering;
                            $amenities = $prop->propertyAmenities;
                            $floorplans = $prop->propertyFloorplan;
                            $documents = $prop->propertyDocumentModel;
                        @endphp
                        <!-- Master Row -->
                        <tr class="hover:bg-slate-50/90 dark:hover:bg-slate-800/50 transition property-row" id="row-{{ $prop->id }}">
                            <!-- ID -->
                            <td class="px-6 py-5 text-center">
                                <span class="font-mono font-bold text-xs px-2.5 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                    #{{ $prop->id }}
                                </span>
                            </td>

                            <!-- Property Asset (Thumbnail + Name + Subtitle) -->
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-14 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shrink-0 shadow-sm flex items-center justify-center">
                                        @if($thumb && !empty($thumb->property_image_value))
                                            <img src="{{ asset('storage/' . $thumb->property_image_value) }}" alt="{{ $prop->name }}" class="w-full h-full object-cover" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' fill=\'%2364748b\'><rect width=\'100\' height=\'100\' fill=\'%23e2e8f0\'/><text x=\'50%\' y=\'50%\' font-size=\'10\' text-anchor=\'middle\' dy=\'.3em\'>Estate</text></svg>'">
                                        @else
                                            <i class="bi bi-building text-slate-400 text-2xl"></i>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('manage-property.edit', ['manage_property' => $prop->id]) }}" class="font-bold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition text-base block truncate">
                                            {{ $prop->name }}
                                        </a>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5">
                                            <i class="bi bi-shield-check text-indigo-500"></i> {{ $prop->management_company ?? 'Asset Management' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Availability Status -->
                            <td class="px-6 py-5">
                                @if (strtolower($prop->availability) === 'available')
                                    <span class="badge-tw badge-success-tw text-xs py-1 px-3">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Available
                                    </span>
                                @elseif (str_contains(strtolower($prop->availability), 'active'))
                                    <span class="badge-tw badge-info-tw text-xs py-1 px-3">
                                        <i class="bi bi-clock-history"></i> {{ $prop->availability }}
                                    </span>
                                @else
                                    <span class="badge-tw badge-warning-tw text-xs py-1 px-3">
                                        {{ $prop->availability ?? 'Listed' }}
                                    </span>
                                @endif
                            </td>

                            <!-- Location -->
                            <td class="px-6 py-5">
                                @if($addr && ($addr->city || $addr->state))
                                    <div class="flex items-center gap-1.5 font-semibold text-slate-900 dark:text-slate-100">
                                        <i class="bi bi-geo-alt-fill text-rose-500 text-sm"></i>
                                        <span>{{ $addr->city ?? '' }}{{ $addr->city && $addr->state ? ', ' : '' }}{{ $addr->state ?? '' }}</span>
                                    </div>
                                    <span class="text-xs text-slate-400 dark:text-slate-500 block truncate max-w-[200px] mt-0.5">
                                        {{ $addr->address_1 ?? 'Address configured' }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400 italic">No address set</span>
                                @endif
                            </td>

                            <!-- Valuation / Price -->
                            <td class="px-6 py-5">
                                @if($details && $details->value)
                                    <div class="font-bold text-slate-900 dark:text-white text-base">
                                        ${{ number_format($details->value) }}
                                    </div>
                                    <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium block">
                                        Appraised Valuation
                                    </span>
                                @elseif($offering && $offering->offering_purchase)
                                    <div class="font-bold text-slate-900 dark:text-white text-base">
                                        ${{ number_format($offering->offering_purchase) }}
                                    </div>
                                    <span class="text-[11px] text-indigo-500 font-medium block">
                                        Offering Target
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400 italic">Unpriced</span>
                                @endif
                            </td>

                            <!-- Actions & Expand Drawer Button -->
                            <td class="px-6 py-5 text-right pr-8">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Expand Details Button -->
                                    <button type="button" onclick="toggleRowDetails({{ $prop->id }})" id="btn-toggle-{{ $prop->id }}" class="px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition flex items-center gap-1.5 shadow-sm">
                                        <i class="bi bi-chevron-down text-indigo-600 dark:text-indigo-400 transition-transform duration-200" id="icon-toggle-{{ $prop->id }}"></i>
                                        <span>View Details</span>
                                    </button>

                                    <!-- Edit Full Property Button -->
                                    <a href="{{ route('manage-property.edit', ['manage_property' => $prop->id]) }}" class="px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition flex items-center gap-1.5 shadow-sm" title="Edit Primary Property & Stages">
                                        <i class="bi bi-pencil-square"></i>
                                        <span>Edit</span>
                                    </a>

                                    <!-- Preview Public Page Link -->
                                    <a href="{{ url('/property_singlepage?id=' . $prop->id) }}" target="_blank" class="p-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition" title="Preview Live Listing">
                                        <i class="bi bi-box-arrow-up-right text-xs"></i>
                                    </a>

                                    <!-- Delete Button -->
                                    <button type="button" class="p-2 rounded-lg border border-rose-200 dark:border-rose-900 bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/60 transition" title="Delete Property" onclick="if(confirm('Are you sure you want to delete property #{{ $prop->id }} ({{ addslashes($prop->name) }})?')) { document.getElementById('delete-form-{{ $prop->id }}').submit(); }">
                                        <i class="bi bi-trash text-xs"></i>
                                    </button>

                                    <form id="delete-form-{{ $prop->id }}" action="{{ route('manage-property.destroy', ['manage_property' => $prop->id]) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Expandable Details Row Drawer -->
                        <tr id="details-row-{{ $prop->id }}" class="hidden bg-slate-50/80 dark:bg-slate-900/90 border-y-2 border-indigo-500/20">
                            <td colspan="6" class="p-6">
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                                    <!-- Card 1: Specifications & Physical Details -->
                                    <div class="p-5 rounded-xl bg-white dark:bg-slate-800/90 border border-slate-200 dark:border-slate-700 shadow-sm space-y-4">
                                        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700/60 pb-3">
                                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                                <i class="bi bi-sliders text-indigo-600 dark:text-indigo-400"></i> Structural Specs
                                            </h3>
                                            <a href="{{ route('admin.manage-property.edit-details', ['id' => $prop->id]) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-semibold flex items-center gap-1">
                                                <i class="bi bi-pencil"></i> Edit Specs
                                            </a>
                                        </div>

                                        @if($details)
                                            <div class="grid grid-cols-2 gap-3 text-xs">
                                                <div>
                                                    <span class="text-slate-400 block">Property Type:</span>
                                                    <strong class="text-slate-800 dark:text-slate-200 font-semibold">{{ $details->type ?? 'Single Family' }}</strong>
                                                </div>
                                                <div>
                                                    <span class="text-slate-400 block">Bed / Bath:</span>
                                                    <strong class="text-slate-800 dark:text-slate-200 font-semibold">{{ $details->bedrooms ?? '-' }} Beds &bull; {{ $details->baths ?? '-' }} Baths</strong>
                                                </div>
                                                <div>
                                                    <span class="text-slate-400 block">Living Area:</span>
                                                    <strong class="text-slate-800 dark:text-slate-200 font-semibold">{{ $details->square_feets ? number_format($details->square_feets) . ' SqFt' : 'N/A' }}</strong>
                                                </div>
                                                <div>
                                                    <span class="text-slate-400 block">Guest Capacity:</span>
                                                    <strong class="text-slate-800 dark:text-slate-200 font-semibold">Sleeps {{ $details->sleeps ?? 'N/A' }}</strong>
                                                </div>
                                                <div>
                                                    <span class="text-slate-400 block">Garages:</span>
                                                    <strong class="text-slate-800 dark:text-slate-200 font-semibold">{{ $details->garages ?? '0' }} Cars</strong>
                                                </div>
                                                <div>
                                                    <span class="text-slate-400 block">Year Built:</span>
                                                    <strong class="text-slate-800 dark:text-slate-200 font-semibold">{{ $details->year_built ?? 'N/A' }}</strong>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-xs text-slate-400 italic">No structural specifications recorded.</p>
                                        @endif

                                        <!-- Amenities Badges -->
                                        <div class="pt-3 border-t border-slate-100 dark:border-slate-700/60">
                                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-2">Amenities Highlights:</span>
                                            <div class="flex flex-wrap gap-1.5 max-h-24 overflow-y-auto">
                                                @if(count($amenities) > 0)
                                                    @foreach(explode(',', $amenities[0]->property_amenities) as $tag)
                                                        @if(trim($tag))
                                                            <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                                                                {{ trim($tag) }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span class="text-xs text-slate-400 italic">No amenities added yet.</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card 2: Financials & Capital Offering -->
                                    <div class="p-5 rounded-xl bg-white dark:bg-slate-800/90 border border-slate-200 dark:border-slate-700 shadow-sm space-y-4">
                                        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700/60 pb-3">
                                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                                <i class="bi bi-tag text-emerald-600 dark:text-emerald-400"></i> Capital Offering & Valuation
                                            </h3>
                                            <a href="{{ route('admin.manage-property.edit-property-offerings', ['id' => $prop->id]) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-semibold flex items-center gap-1">
                                                <i class="bi bi-pencil"></i> Edit Offering
                                            </a>
                                        </div>

                                        <div class="space-y-2.5 text-xs">
                                            <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 dark:bg-slate-900/60">
                                                <span class="text-slate-500">Appraised Valuation:</span>
                                                <strong class="text-slate-900 dark:text-white font-bold text-sm">
                                                    {{ $details && $details->value ? '$' . number_format($details->value) : 'Unpriced' }}
                                                </strong>
                                            </div>

                                            @if($offering)
                                                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 dark:bg-slate-900/60">
                                                    <span class="text-slate-500">Offering Purchase Price:</span>
                                                    <strong class="text-indigo-600 dark:text-indigo-400 font-semibold">
                                                        {{ $offering->offering_purchase ? '$' . number_format($offering->offering_purchase) : 'N/A' }}
                                                    </strong>
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <span class="text-slate-400">Renovation / Build Cost:</span>
                                                    <span class="font-medium text-slate-700 dark:text-slate-300">{{ $offering->offering_build_cost ? '$' . number_format($offering->offering_build_cost) : '-' }}</span>
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <span class="text-slate-400">Capital Improvements:</span>
                                                    <span class="font-medium text-slate-700 dark:text-slate-300">{{ $offering->offering_improvements ? '$' . number_format($offering->offering_improvements) : '-' }}</span>
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <span class="text-slate-400">Closing & Escrow Fees:</span>
                                                    <span class="font-medium text-slate-700 dark:text-slate-300">{{ $offering->offering_closing_cost ? '$' . number_format($offering->offering_closing_cost) : '-' }}</span>
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <span class="text-slate-400">Sourcing / Acquisition Fees:</span>
                                                    <span class="font-medium text-slate-700 dark:text-slate-300">{{ $offering->offering_sourcing_fees ? '$' . number_format($offering->offering_sourcing_fees) : '-' }}</span>
                                                </div>
                                            @else
                                                <p class="text-xs text-slate-400 italic">No offering breakdown configured.</p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Card 3: Direct Sub-Module Navigation Links & Files -->
                                    <div class="p-5 rounded-xl bg-white dark:bg-slate-800/90 border border-slate-200 dark:border-slate-700 shadow-sm space-y-4">
                                        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700/60 pb-3">
                                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                                <i class="bi bi-grid-fill text-indigo-600 dark:text-indigo-400"></i> Edit Stages & Sub-Modules
                                            </h3>
                                            <span class="badge-tw badge-info-tw text-[10px]">7 Stages</span>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2 text-xs">
                                            <!-- 1. Primary -->
                                            <a href="{{ route('manage-property.edit', ['manage_property' => $prop->id]) }}" class="p-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 hover:border-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition flex items-center gap-2">
                                                <i class="bi bi-building text-indigo-500"></i>
                                                <span class="font-medium">1. Primary Details</span>
                                            </a>

                                            <!-- 2. Address -->
                                            <a href="{{ route('admin.manage-property.edit-address', ['id' => $prop->id]) }}" class="p-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 hover:border-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition flex items-center gap-2">
                                                <i class="bi bi-geo-alt text-rose-500"></i>
                                                <span class="font-medium">2. Address Location</span>
                                            </a>

                                            <!-- 3. Specs -->
                                            <a href="{{ route('admin.manage-property.edit-details', ['id' => $prop->id]) }}" class="p-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 hover:border-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition flex items-center gap-2">
                                                <i class="bi bi-sliders text-amber-500"></i>
                                                <span class="font-medium">3. Specifications</span>
                                            </a>

                                            <!-- 4. Amenities -->
                                            <a href="{{ route('admin.manage-property.edit-amenities', ['id' => $prop->id]) }}" class="p-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 hover:border-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition flex items-center gap-2">
                                                <i class="bi bi-stars text-violet-500"></i>
                                                <span class="font-medium">4. Amenities</span>
                                            </a>

                                            <!-- 5. Floorplans -->
                                            <a href="{{ route('admin.manage-property.edit-floorplan', ['id' => $prop->id]) }}" class="p-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 hover:border-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <i class="bi bi-bounding-box text-cyan-500"></i>
                                                    <span class="font-medium">5. Floorplans</span>
                                                </div>
                                                <span class="badge-tw badge-neutral-tw text-[10px]">{{ count($floorplans) }}</span>
                                            </a>

                                            <!-- 6. Offerings -->
                                            <a href="{{ route('admin.manage-property.edit-property-offerings', ['id' => $prop->id]) }}" class="p-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 hover:border-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition flex items-center gap-2">
                                                <i class="bi bi-tag text-emerald-500"></i>
                                                <span class="font-medium">6. Offerings</span>
                                            </a>
                                        </div>

                                        <!-- 7. Legal Documents Action Link -->
                                        <div class="pt-2 border-t border-slate-100 dark:border-slate-700/60">
                                            <a href="{{ route('admin.manage-property.edit-property-documents', ['id' => $prop->id]) }}" class="w-full p-2.5 rounded-lg border border-indigo-200 dark:border-indigo-900 bg-indigo-50 dark:bg-indigo-950/40 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 transition flex items-center justify-between text-xs font-semibold">
                                                <div class="flex items-center gap-2">
                                                    <i class="bi bi-file-earmark-pdf-fill text-rose-500"></i>
                                                    <span>7. Legal Documentation & Disclosures</span>
                                                </div>
                                                <span class="badge-tw badge-info-tw text-[10px]">{{ count($documents) }} PDFs</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-slate-400 dark:text-slate-500">
                                <div class="max-w-sm mx-auto space-y-3">
                                    <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto text-2xl text-slate-400">
                                        <i class="bi bi-inbox"></i>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-200">No Properties Found</h3>
                                    <p class="text-xs text-slate-500">Your portfolio is currently empty. Get started by creating your first fractional property listing.</p>
                                    <a href="{{ route('manage-property.create') }}" class="btn-primary-tw text-xs py-2 px-4 inline-flex items-center gap-1.5 mt-2">
                                        <i class="bi bi-plus-lg"></i> Create Property
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination & Summary Footer -->
        @if ($property->hasPages() || $property->total() > 0)
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/60 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-slate-500 dark:text-slate-400">
                    Showing <strong class="font-semibold text-slate-800 dark:text-slate-200">{{ $property->firstItem() ?? 0 }}</strong> to <strong class="font-semibold text-slate-800 dark:text-slate-200">{{ $property->lastItem() ?? 0 }}</strong> of <strong class="font-semibold text-slate-800 dark:text-slate-200">{{ number_format($property->total()) }}</strong> total assets
                </div>
                <div class="pagination-container">
                    {{ $property->onEachSide(2)->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function toggleRowDetails(id) {
        const detailsRow = document.getElementById('details-row-' + id);
        const icon = document.getElementById('icon-toggle-' + id);
        const btn = document.getElementById('btn-toggle-' + id);

        if (detailsRow.classList.contains('hidden')) {
            detailsRow.classList.remove('hidden');
            icon.classList.add('rotate-180');
            btn.classList.add('bg-indigo-50', 'dark:bg-indigo-950/60', 'text-indigo-700', 'dark:text-indigo-300', 'border-indigo-300', 'dark:border-indigo-700');
        } else {
            detailsRow.classList.add('hidden');
            icon.classList.remove('rotate-180');
            btn.classList.remove('bg-indigo-50', 'dark:bg-indigo-950/60', 'text-indigo-700', 'dark:text-indigo-300', 'border-indigo-300', 'dark:border-indigo-700');
        }
    }

    function filterPropertyTable() {
        const input = document.getElementById('propertyTableSearch');
        const filter = input.value.toLowerCase();
        const rows = document.querySelectorAll('#propertyTable tbody tr.property-row');

        rows.forEach(row => {
            const id = row.id.replace('row-', '');
            const detailsRow = document.getElementById('details-row-' + id);
            const text = (row.innerText + ' ' + (detailsRow ? detailsRow.innerText : '')).toLowerCase();

            if (text.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
                if (detailsRow && !detailsRow.classList.contains('hidden')) {
                    detailsRow.classList.add('hidden');
                }
            }
        });
    }
</script>
@endsection
