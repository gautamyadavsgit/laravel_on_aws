@extends('admin.common.page')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
            <i class="bi bi-building text-indigo-600 dark:text-indigo-400"></i> Property Management
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">View, configure, and maintain all real estate properties and fractional offerings.</p>
    </div>
    <div>
        <a href="{{ route('manage-property.create') }}" class="btn-primary-tw">
            <i class="bi bi-plus-lg"></i> Add New Property
        </a>
    </div>
</div>

<div class="card-tw p-0 overflow-hidden">
    <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
        <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
            <i class="bi bi-list-ul text-indigo-600 dark:text-indigo-400"></i> All Properties List
        </h2>
        <span class="badge-tw badge-info-tw">{{ number_format($property->total()) }} Listed</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
            <thead class="bg-slate-50 dark:bg-slate-800/60 text-xs uppercase font-semibold text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                <tr>
                    <th class="px-5 py-3.5 w-16">ID</th>
                    <th class="px-5 py-3.5">Status</th>
                    <th class="px-5 py-3.5">Property Name</th>
                    <th class="px-5 py-3.5">Management Co.</th>
                    <th class="px-5 py-3.5">Description</th>
                    <th class="px-5 py-3.5 w-44">Actions</th>
                    <th class="px-5 py-3.5">Module Sub-Forms</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                @forelse ($property as $prop)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                        <td class="px-5 py-4 font-mono font-bold text-slate-900 dark:text-slate-100">#{{ $prop->id }}</td>
                        <td class="px-5 py-4">
                            @if (strtolower($prop->availability) === 'available' || str_contains(strtolower($prop->availability), 'active'))
                                <span class="badge-tw badge-success-tw">
                                    <i class="bi bi-check-circle-fill"></i> {{ $prop->availability }}
                                </span>
                            @else
                                <span class="badge-tw badge-info-tw">
                                    <i class="bi bi-pie-chart-fill"></i> {{ $prop->availability ?? 'Listed' }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 font-semibold text-slate-900 dark:text-white">
                            {{ $prop->name }}
                        </td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-400">{{ $prop->management_company ?? 'N/A' }}</td>
                        <td class="px-5 py-4 text-slate-500 dark:text-slate-400 max-w-xs truncate">
                            {{ Str::limit($prop->description, 65) }}
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('manage-property.show', ['manage_property' => $prop->id]) }}" class="px-2.5 py-1 rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition" title="View Property Details">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="{{ route('manage-property.edit', ['manage_property' => $prop->id]) }}" class="px-2.5 py-1 rounded-md border border-indigo-200 dark:border-indigo-800 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 text-xs font-medium hover:bg-indigo-100 dark:hover:bg-indigo-900 transition" title="Edit Main Property">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <button type="button" class="px-2 py-1 rounded-md border border-rose-200 dark:border-rose-800 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 text-xs font-medium hover:bg-rose-100 dark:hover:bg-rose-900 transition" title="Delete Property" onclick="if(confirm('Are you sure you want to delete this property?')) { document.getElementById('delete-form-{{ $prop->id }}').submit(); }">
                                    <i class="bi bi-trash"></i>
                                </button>

                                <form id="delete-form-{{ $prop->id }}" action="{{ route('manage-property.destroy', ['manage_property' => $prop->id]) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex flex-wrap gap-1.5 max-w-lg">
                                <a href="{{ route('admin.manage-property.edit-address', ['id' => $prop->id]) }}" class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-indigo-50 hover:text-indigo-700 dark:hover:bg-indigo-950 dark:hover:text-indigo-300 transition"><i class="bi bi-geo-alt"></i> Address</a>
                                <a href="{{ route('admin.manage-property.edit-details', ['id' => $prop->id]) }}" class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-indigo-50 hover:text-indigo-700 dark:hover:bg-indigo-950 dark:hover:text-indigo-300 transition"><i class="bi bi-info-circle"></i> Details</a>
                                <a href="{{ route('admin.manage-property.edit-amenities', ['id' => $prop->id]) }}" class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-indigo-50 hover:text-indigo-700 dark:hover:bg-indigo-950 dark:hover:text-indigo-300 transition"><i class="bi bi-stars"></i> Amenities</a>
                                <a href="{{ route('admin.manage-property.edit-market', ['id' => $prop->id]) }}" class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-indigo-50 hover:text-indigo-700 dark:hover:bg-indigo-950 dark:hover:text-indigo-300 transition"><i class="bi bi-graph-up"></i> Market</a>
                                <a href="{{ route('admin.manage-property.edit-floorplan', ['id' => $prop->id]) }}" class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-indigo-50 hover:text-indigo-700 dark:hover:bg-indigo-950 dark:hover:text-indigo-300 transition"><i class="bi bi-bounding-box"></i> Floorplan</a>
                                <a href="{{ route('admin.manage-property.edit-property-extra-details', ['id' => $prop->id]) }}" class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-indigo-50 hover:text-indigo-700 dark:hover:bg-indigo-950 dark:hover:text-indigo-300 transition"><i class="bi bi-plus-square"></i> Extra</a>
                                <a href="{{ route('admin.manage-property.edit-property-aacf', ['id' => $prop->id]) }}" class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-indigo-50 hover:text-indigo-700 dark:hover:bg-indigo-950 dark:hover:text-indigo-300 transition"><i class="bi bi-cash-coin"></i> AACF</a>
                                <a href="{{ route('admin.manage-property.edit-property-urls', ['id' => $prop->id]) }}" class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-indigo-50 hover:text-indigo-700 dark:hover:bg-indigo-950 dark:hover:text-indigo-300 transition"><i class="bi bi-link-45deg"></i> URLs</a>
                                <a href="{{ route('admin.manage-property.edit-property-offerings', ['id' => $prop->id]) }}" class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-indigo-50 hover:text-indigo-700 dark:hover:bg-indigo-950 dark:hover:text-indigo-300 transition"><i class="bi bi-tag"></i> Offerings</a>
                                <a href="{{ route('admin.manage-property.edit-property-shares', ['id' => $prop->id]) }}" class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-indigo-50 hover:text-indigo-700 dark:hover:bg-indigo-950 dark:hover:text-indigo-300 transition"><i class="bi bi-pie-chart"></i> Shares</a>
                                <a href="{{ route('admin.manage-property.edit-property-financial-details', ['id' => $prop->id]) }}" class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-indigo-50 hover:text-indigo-700 dark:hover:bg-indigo-950 dark:hover:text-indigo-300 transition"><i class="bi bi-currency-dollar"></i> Financial</a>
                                <a href="{{ route('admin.manage-property.edit-property-calc-presets', ['id' => $prop->id]) }}" class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-indigo-50 hover:text-indigo-700 dark:hover:bg-indigo-950 dark:hover:text-indigo-300 transition"><i class="bi bi-calculator"></i> Calc</a>
                                <a href="{{ route('admin.manage-property.edit-property-documents', ['id' => $prop->id]) }}" class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-indigo-50 hover:text-indigo-700 dark:hover:bg-indigo-950 dark:hover:text-indigo-300 transition"><i class="bi bi-file-earmark-pdf"></i> Docs</a>
                                <a href="{{ route('admin.manage-property.edit-property-taxes', ['id' => $prop->id]) }}" class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-indigo-50 hover:text-indigo-700 dark:hover:bg-indigo-950 dark:hover:text-indigo-300 transition"><i class="bi bi-receipt"></i> Taxes</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-slate-400 dark:text-slate-500">
                            <i class="bi bi-inbox text-4xl block mb-2 text-slate-300 dark:text-slate-600"></i>
                            No properties found. <a href="{{ route('manage-property.create') }}" class="text-indigo-600 dark:text-indigo-400 font-semibold hover:underline">Click here to add your first property</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($property->hasPages())
        <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/60 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-xs text-slate-500 dark:text-slate-400">
                Showing <strong class="font-semibold text-slate-800 dark:text-slate-200">{{ $property->firstItem() ?? 0 }}</strong> to <strong class="font-semibold text-slate-800 dark:text-slate-200">{{ $property->lastItem() ?? 0 }}</strong> of <strong class="font-semibold text-slate-800 dark:text-slate-200">{{ number_format($property->total()) }}</strong> total properties
            </div>
            <div>
                {{ $property->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
