@extends('admin.common.page')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition mb-3">
                <i class="bi bi-arrow-left"></i> Back to Users
            </a>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-indigo-600/10 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl font-bold">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $user->first_name }} {{ $user->last_name }}</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Investor profile and property interest overview</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="card-tw p-6 xl:col-span-1">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-4">
                <i class="bi bi-person-vcard text-indigo-600 dark:text-indigo-400"></i> Investor Details
            </h2>
            <div class="space-y-3 text-sm text-slate-600 dark:text-slate-300">
                <div class="flex items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-2">
                    <span>Name</span>
                    <strong class="text-slate-900 dark:text-white">{{ $user->first_name }} {{ $user->last_name }}</strong>
                </div>
                <div class="flex items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-2">
                    <span>Email</span>
                    <strong class="text-slate-900 dark:text-white break-all">{{ $user->email }}</strong>
                </div>
                <div class="flex items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-2">
                    <span>Phone</span>
                    <strong class="text-slate-900 dark:text-white">{{ $user->phone ?? 'N/A' }}</strong>
                </div>
                <div class="flex items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-2">
                    <span>Status</span>
                    @if($user->email_verified_at)
                        <span class="badge-tw badge-success-tw text-xs py-1 px-2">Verified</span>
                    @else
                        <span class="badge-tw badge-warning-tw text-xs py-1 px-2">Unverified</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-tw p-0 overflow-hidden xl:col-span-2">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between gap-4">
                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-building-fill-check text-indigo-600 dark:text-indigo-400"></i> Interested Properties
                </h2>
                <span class="badge-tw badge-info-tw py-1.5 px-3 text-xs font-semibold whitespace-nowrap">
                    {{ $user->propertyInterests->count() }} interest(s)
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-800/80 text-xs uppercase font-semibold text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="px-6 py-4">Property</th>
                            <th class="px-6 py-4">Location</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Submitted</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @forelse($user->propertyInterests as $interest)
                            @php $property = $interest->property; @endphp
                            <tr class="hover:bg-slate-50/90 dark:hover:bg-slate-800/50 transition">
                                <td class="px-6 py-5">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $property->name ?? 'Property #' . $interest->property_id }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">ID #{{ $property->id ?? $interest->property_id }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    @if($property && $property->propertyAddress)
                                        <span>{{ $property->propertyAddress->city ?? '' }}{{ $property->propertyAddress->city && $property->propertyAddress->state ? ', ' : '' }}{{ $property->propertyAddress->state ?? '' }}</span>
                                    @else
                                        <span class="text-slate-400 italic">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    <span class="badge-tw badge-success-tw text-xs py-1 px-2 uppercase">{{ $interest->status }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    {{ $interest->created_at ? $interest->created_at->format('M d, Y') : 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                    No property interests recorded for this investor yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
