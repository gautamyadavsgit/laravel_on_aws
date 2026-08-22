@extends('admin.common.page')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-600/10 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl font-bold">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">User Management</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Manage registered investors, accreditation status, and profiles.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.create') }}" class="btn-primary-tw">
                <i class="bi bi-plus-lg"></i>
                <span>Add New User</span>
            </a>
        </div>
    </div>

    <!-- Search & Filters Toolbar -->
    <div class="card-tw p-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Keyword Search -->
            <div class="relative">
                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search name, email, phone..." 
                    class="input-tw pl-9 text-xs"
                />
            </div>

            <!-- Verification Status Filter -->
            <div>
                <select name="status" class="select-tw text-xs">
                    <option value="">All Verification Status</option>
                    <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified Only</option>
                    <option value="unverified" {{ request('status') === 'unverified' ? 'selected' : '' }}>Unverified Only</option>
                </select>
            </div>

            <!-- Accreditation Filter -->
            <div>
                <select name="accreditation" class="select-tw text-xs">
                    <option value="">All Accreditation Types</option>
                    @foreach($accreditationStatuses as $status)
                        <option value="{{ $status->id }}" {{ request('accreditation') == $status->id ? 'selected' : '' }}>
                            {{ Str::limit($status->value, 35) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="btn-primary-tw py-2 px-3 text-xs w-full justify-center">
                    <i class="bi bi-funnel-fill"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'accreditation']))
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary-tw py-2 px-3 text-xs whitespace-nowrap" title="Clear Filters">
                        <i class="bi bi-x-circle"></i> Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table Card -->
    <div class="card-tw p-0 overflow-hidden shadow-sm">
        <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between gap-3">
            <span class="badge-tw badge-info-tw py-1.5 px-3 text-xs font-semibold">
                <i class="bi bi-person-lines-fill"></i> {{ $users->total() }} Total Users
            </span>
            <span class="text-xs text-slate-500 dark:text-slate-400">
                Page {{ $users->currentPage() }} of {{ $users->lastPage() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-800/80 text-xs uppercase font-semibold text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-4">User / Investor</th>
                        <th class="px-6 py-4">Contact Info</th>
                        <th class="px-6 py-4">Verification</th>
                        <th class="px-6 py-4">Accreditation</th>
                        <th class="px-6 py-4">Interests</th>
                        <th class="px-6 py-4 text-right pr-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/90 dark:hover:bg-slate-800/50 transition">
                            <!-- User Name & Company -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-700 text-white flex items-center justify-center font-bold text-sm shadow-sm shrink-0">
                                        {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($user->last_name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 dark:text-white truncate">
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </div>
                                        @if($user->company_name)
                                            <div class="text-xs text-indigo-600 dark:text-indigo-400 truncate flex items-center gap-1">
                                                <i class="bi bi-briefcase"></i> {{ $user->company_name }}
                                            </div>
                                        @else
                                            <div class="text-xs text-slate-400 dark:text-slate-500">Individual Investor</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Contact Info -->
                            <td class="px-6 py-4">
                                <div class="space-y-0.5">
                                    <div class="text-xs font-medium text-slate-800 dark:text-slate-200 truncate flex items-center gap-1.5">
                                        <i class="bi bi-envelope text-slate-400"></i> {{ $user->email }}
                                    </div>
                                    @if($user->phone)
                                        <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                                            <i class="bi bi-telephone text-slate-400"></i> {{ $user->phone }}
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Verification Status -->
                            <td class="px-6 py-4">
                                @if($user->email_verified_at)
                                    <span class="badge-tw badge-success-tw text-xs py-1 px-2.5">
                                        <i class="bi bi-patch-check-fill"></i> Verified
                                    </span>
                                @else
                                    <span class="badge-tw badge-warning-tw text-xs py-1 px-2.5">
                                        <i class="bi bi-clock-history"></i> Pending
                                    </span>
                                @endif
                            </td>

                            <!-- Accreditation Tier -->
                            <td class="px-6 py-4">
                                @if($user->accreditationStatus)
                                    <span class="badge-tw badge-neutral-tw text-[11px] py-1 px-2 max-w-[200px] truncate block" title="{{ $user->accreditationStatus->value }}">
                                        {{ Str::limit($user->accreditationStatus->value, 24) }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400 italic">Not specified</span>
                                @endif
                            </td>

                            <!-- Interested Properties -->
                            <td class="px-6 py-4">
                                @php $interestCount = $user->propertyInterests->count(); @endphp
                                @if($interestCount > 0)
                                    <span class="badge-tw badge-info-tw text-xs py-1 px-2">
                                        <i class="bi bi-building"></i> {{ $interestCount }} property{{ $interestCount > 1 ? 'ies' : '' }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400 italic">None</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right pr-6">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- View Button -->
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="p-1.5 rounded-lg text-slate-600 hover:text-indigo-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-indigo-400 dark:hover:bg-slate-800 transition" title="View Profile">
                                        <i class="bi bi-eye text-base"></i>
                                    </a>

                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="p-1.5 rounded-lg text-slate-600 hover:text-amber-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-amber-400 dark:hover:bg-slate-800 transition" title="Edit User">
                                        <i class="bi bi-pencil-square text-base"></i>
                                    </a>

                                    <!-- Delete Button Trigger -->
                                    <button 
                                        type="button" 
                                        onclick="openDeleteModal('{{ $user->id }}', '{{ addslashes($user->first_name . ' ' . $user->last_name) }}')"
                                        class="p-1.5 rounded-lg text-slate-600 hover:text-rose-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-rose-400 dark:hover:bg-slate-800 transition" 
                                        title="Delete User"
                                    >
                                        <i class="bi bi-trash text-base"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center text-2xl mx-auto mb-3">
                                    <i class="bi bi-people"></i>
                                </div>
                                <p class="font-medium text-slate-700 dark:text-slate-300">No users found</p>
                                <p class="text-xs text-slate-400 mt-1">Try adjusting your search criteria or create a new user.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                {{ $users->links() }}
            </div>
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
