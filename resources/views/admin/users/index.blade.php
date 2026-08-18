@extends('admin.common.page')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-600/10 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl font-bold">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Investor User Management</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Review all registered investors and the properties they have expressed interest in.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card-tw p-0 overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
            <div class="flex items-center justify-between gap-3">
                <span class="badge-tw badge-info-tw py-1.5 px-3 text-xs font-semibold whitespace-nowrap">
                    <i class="bi bi-person-lines-fill"></i> {{ $users->total() }} Users
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-800/80 text-xs uppercase font-semibold text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Investor</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Verified</th>
                        <th class="px-6 py-4">Interested Properties</th>
                        <th class="px-6 py-4 text-right pr-8">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/90 dark:hover:bg-slate-800/50 transition">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 flex items-center justify-center font-semibold">
                                        {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($user->last_name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 dark:text-white">
                                            {{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}
                                        </div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $user->phone ?? 'No phone listed' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="font-medium text-slate-700 dark:text-slate-200">{{ $user->email }}</span>
                            </td>
                            <td class="px-6 py-5">
                                @if($user->email_verified_at)
                                    <span class="badge-tw badge-success-tw text-xs py-1 px-2">Verified</span>
                                @else
                                    <span class="badge-tw badge-warning-tw text-xs py-1 px-2">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-wrap gap-2">
                                    @php $count = $user->propertyInterests->count(); @endphp
                                    @if($count)
                                        @foreach($user->propertyInterests->take(3) as $interest)
                                            <span class="badge-tw badge-info-tw text-[10px] px-2 py-1">
                                                {{ $interest->property->name ?? 'Property #' . $interest->property_id }}
                                            </span>
                                        @endforeach
                                        @if($count > 3)
                                            <span class="badge-tw badge-secondary-tw text-[10px] px-2 py-1">+{{ $count - 3 }} more</span>
                                        @endif
                                    @else
                                        <span class="text-xs text-slate-400 italic">No property interest yet</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right pr-8">
                                <a href="{{ route('admin.users.show', ['user' => $user->id]) }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition shadow-sm">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                No users have registered yet.
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
@endsection
