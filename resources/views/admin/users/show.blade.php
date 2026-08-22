@extends('admin.common.page')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition mb-2">
                <i class="bi bi-arrow-left"></i> Back to Users List
            </a>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 text-white flex items-center justify-center text-xl font-bold shadow-md shrink-0">
                    {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($user->last_name ?? 'S', 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                        <span>{{ $user->first_name }} {{ $user->middile_name }} {{ $user->last_name }} {{ $user->suffix }}</span>
                        @if($user->email_verified_at)
                            <span class="badge-tw badge-success-tw text-xs py-0.5 px-2">
                                <i class="bi bi-patch-check-fill"></i> Verified
                            </span>
                        @else
                            <span class="badge-tw badge-warning-tw text-xs py-0.5 px-2">
                                <i class="bi bi-clock-history"></i> Unverified
                            </span>
                        @endif
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Member since {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }} • Investor ID #{{ $user->id }}
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-primary-tw">
                <i class="bi bi-pencil-square"></i>
                <span>Edit User</span>
            </a>
            <button 
                type="button" 
                onclick="openDeleteModal('{{ $user->id }}', '{{ addslashes($user->name) }}')"
                class="btn-danger-tw"
            >
                <i class="bi bi-trash"></i>
                <span>Delete</span>
            </button>
        </div>
    </div>

    <!-- Main Grid: Info Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 1. Contact & Identity Information -->
        <div class="card-tw space-y-4">
            <div class="border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center justify-between">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-person-vcard text-indigo-600 dark:text-indigo-400"></i> Contact & Identity
                </h2>
                <span class="text-xs text-slate-400">Personal Info</span>
            </div>

            <div class="space-y-3 text-xs text-slate-600 dark:text-slate-300">
                <div class="flex items-start justify-between gap-3 border-b border-slate-100/80 dark:border-slate-800/80 pb-2">
                    <span class="text-slate-400">Email</span>
                    <span class="font-semibold text-slate-900 dark:text-white text-right break-all">{{ $user->email }}</span>
                </div>

                <div class="flex items-center justify-between gap-3 border-b border-slate-100/80 dark:border-slate-800/80 pb-2">
                    <span class="text-slate-400">Primary Phone</span>
                    <span class="font-medium text-slate-900 dark:text-white">{{ $user->phone ?? 'N/A' }}</span>
                </div>

                <div class="flex items-center justify-between gap-3 border-b border-slate-100/80 dark:border-slate-800/80 pb-2">
                    <span class="text-slate-400">Alternate Phone</span>
                    <span class="font-medium text-slate-900 dark:text-white">{{ $user->alternate_phone ?? 'N/A' }}</span>
                </div>

                <div class="flex items-center justify-between gap-3 border-b border-slate-100/80 dark:border-slate-800/80 pb-2">
                    <span class="text-slate-400">Company</span>
                    <span class="font-medium text-slate-900 dark:text-white">{{ $user->company_name ?? 'Individual' }}</span>
                </div>

                <div class="flex items-center justify-between gap-3 border-b border-slate-100/80 dark:border-slate-800/80 pb-2">
                    <span class="text-slate-400">Date of Birth</span>
                    <span class="font-medium text-slate-900 dark:text-white">
                        {{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('M d, Y') : 'N/A' }}
                    </span>
                </div>

                <div class="flex items-center justify-between gap-3 border-b border-slate-100/80 dark:border-slate-800/80 pb-2">
                    <span class="text-slate-400">SSN / Tax ID</span>
                    <span class="font-medium text-slate-900 dark:text-white">{{ $user->social_security_number ?? 'N/A' }}</span>
                </div>

                <div class="flex items-start justify-between gap-3 border-b border-slate-100/80 dark:border-slate-800/80 pb-2">
                    <span class="text-slate-400">Address</span>
                    <span class="font-medium text-slate-900 dark:text-white text-right max-w-[200px]">{{ $user->address ?? 'N/A' }}</span>
                </div>

                <div class="flex items-center justify-between gap-3 pt-1">
                    <span class="text-slate-400">App Connected</span>
                    @if($user->app_connected)
                        <span class="badge-tw badge-success-tw text-[10px] py-0.5 px-2">Yes</span>
                    @else
                        <span class="badge-tw badge-neutral-tw text-[10px] py-0.5 px-2">No</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- 2. Investor Suitability & Preferences -->
        <div class="card-tw space-y-4 lg:col-span-2">
            <div class="border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center justify-between">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-pie-chart-fill text-indigo-600 dark:text-indigo-400"></i> Suitability & Investor Profile
                </h2>
                <span class="text-xs text-slate-400">Accreditation Profile</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs text-slate-600 dark:text-slate-300">
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                    <span class="text-slate-400 block mb-1">Accreditation Status</span>
                    <strong class="text-slate-900 dark:text-white text-sm">
                        {{ $user->accreditationStatus->value ?? 'Not Specified' }}
                    </strong>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                    <span class="text-slate-400 block mb-1">Net Worth Tier</span>
                    <strong class="text-slate-900 dark:text-white text-sm">
                        {{ $user->userNetWorth->value ?? 'Not Specified' }}
                    </strong>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                    <span class="text-slate-400 block mb-1">Experience Level</span>
                    <span class="font-medium text-slate-900 dark:text-white">
                        {{ $user->experienceLevel->value ?? 'Not Specified' }}
                    </span>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                    <span class="text-slate-400 block mb-1">Primary Investment Goal</span>
                    <span class="font-medium text-slate-900 dark:text-white">
                        {{ $user->investmentGoal->value ?? 'Not Specified' }}
                    </span>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                    <span class="text-slate-400 block mb-1">Reason for Investing</span>
                    <span class="font-medium text-slate-900 dark:text-white">
                        {{ $user->investingReason->value ?? 'Not Specified' }}
                    </span>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                    <span class="text-slate-400 block mb-1">Source of Funds</span>
                    <span class="font-medium text-slate-900 dark:text-white">
                        {{ $user->investmentSource->value ?? 'Not Specified' }}
                    </span>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                    <span class="text-slate-400 block mb-1">Deployment Timeline</span>
                    <span class="font-medium text-slate-900 dark:text-white">
                        {{ $user->investmentTimeline->value ?? 'Not Specified' }}
                    </span>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                    <span class="text-slate-400 block mb-1">Investment Horizon</span>
                    <span class="font-medium text-slate-900 dark:text-white">
                        {{ $user->investmentTimelength->value ?? 'Not Specified' }}
                    </span>
                </div>

                <div class="sm:col-span-2 p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                    <span class="text-slate-400 block mb-1">Acquisition Source / Referral</span>
                    <span class="font-medium text-slate-900 dark:text-white">
                        {{ $user->hearAboutUs->value ?? 'Direct / Organic' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Interested Properties Table -->
    <div class="card-tw p-0 overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between gap-4">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-building-fill-check text-indigo-600 dark:text-indigo-400"></i> Expressed Property Interests
            </h2>
            <span class="badge-tw badge-info-tw py-1 px-3 text-xs font-semibold">
                {{ $user->propertyInterests->count() }} Record(s)
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-800/80 text-xs uppercase font-semibold text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Property</th>
                        <th class="px-6 py-4">Location</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Inquiry Date</th>
                        <th class="px-6 py-4 text-right pr-6">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse($user->propertyInterests as $interest)
                        @php $property = $interest->property; @endphp
                        <tr class="hover:bg-slate-50/90 dark:hover:bg-slate-800/50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 dark:text-white">{{ $property->name ?? 'Property #' . $interest->property_id }}</div>
                                <div class="text-xs text-slate-400">ID #{{ $property->id ?? $interest->property_id }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                @if($property && $property->propertyAddress)
                                    <span>{{ $property->propertyAddress->city ?? '' }}{{ $property->propertyAddress->city && $property->propertyAddress->state ? ', ' : '' }}{{ $property->propertyAddress->state ?? '' }}</span>
                                @else
                                    <span class="text-slate-400 italic">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="badge-tw badge-success-tw text-xs py-0.5 px-2 uppercase">{{ $interest->status }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">
                                {{ $interest->created_at ? $interest->created_at->format('M d, Y h:i A') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-right pr-6">
                                @if($property)
                                    <a href="{{ route('manage-property.show', $property->id) }}" class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 font-semibold" target="_blank">
                                        <span>View Property</span>
                                        <i class="bi bi-box-arrow-up-right text-[10px]"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-xs text-slate-400">
                                No investment interests recorded for this investor yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Property Favorites Grid -->
    <div class="card-tw">
        <div class="border-b border-slate-100 dark:border-slate-800 pb-3 mb-4 flex items-center justify-between">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-heart-fill text-rose-500"></i> Saved / Favorited Properties
            </h2>
            <span class="badge-tw badge-neutral-tw text-xs py-0.5 px-2">
                {{ $user->propertyFavorites->count() }} Saved
            </span>
        </div>

        @if($user->propertyFavorites->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($user->propertyFavorites as $fav)
                    @php $favProperty = $fav->property; @endphp
                    <div class="p-3.5 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 flex items-center justify-between">
                        <div>
                            <div class="font-bold text-xs text-slate-900 dark:text-white truncate">
                                {{ $favProperty->name ?? 'Property #' . $fav->property_id }}
                            </div>
                            <div class="text-[11px] text-slate-400">
                                Saved {{ $fav->created_at ? $fav->created_at->diffForHumans() : '' }}
                            </div>
                        </div>
                        @if($favProperty)
                            <a href="{{ route('manage-property.show', $favProperty->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 transition" title="View Property">
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-slate-400 italic py-2">This user has not favorited any properties yet.</p>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xl shrink-0">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Delete User</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">This action cannot be undone.</p>
            </div>
        </div>

        <p class="text-sm text-slate-600 dark:text-slate-300">
            Are you sure you want to delete <strong id="deleteUserName" class="text-slate-900 dark:text-white">this user</strong>? All associated activity will be permanently removed.
        </p>

        <form id="deleteForm" method="POST" action="" class="flex items-center justify-end gap-3 pt-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteModal()" class="btn-secondary-tw text-xs py-2 px-4">
                Cancel
            </button>
            <button type="submit" class="btn-danger-tw text-xs py-2 px-4">
                <i class="bi bi-trash-fill"></i> Delete User
            </button>
        </form>
    </div>
</div>

<script>
    function openDeleteModal(userId, userName) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        const nameSpan = document.getElementById('deleteUserName');

        form.action = `/admin/users/${userId}`;
        nameSpan.textContent = userName || 'this user';
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
    }
</script>
@endsection
