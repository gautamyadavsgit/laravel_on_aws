@include('frontend.common.header', ['title' => 'Investor Profile | Gautam Real Estate'])

<div class="py-10 lg:py-14">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition mb-3">
                    <i class="bi bi-arrow-left"></i> {{ __('common.back_to_dashboard') }}
                </a>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ __('profile.profile_title') }}</h1>
            </div>
        </div>

        <div class="card-tw p-6 space-y-6">
            <div class="flex items-center gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div class="w-16 h-16 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 flex items-center justify-center text-2xl font-bold">
                    {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($user->last_name ?? 'S', 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ $user->first_name }} {{ $user->last_name }}</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                    <div class="text-xs uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('profile.full_name') }}</div>
                    <div class="mt-2 font-semibold text-slate-900 dark:text-white">{{ $user->first_name }} {{ $user->last_name }}</div>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                    <div class="text-xs uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('profile.email_address') }}</div>
                    <div class="mt-2 font-semibold text-slate-900 dark:text-white break-all">{{ $user->email }}</div>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                    <div class="text-xs uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('profile.phone_number') }}</div>
                    <div class="mt-2 font-semibold text-slate-900 dark:text-white">{{ $user->phone ?? __('common.not_provided') }}</div>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                    <div class="text-xs uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('profile.verification_status') }}</div>
                    <div class="mt-2">
                        @if($user->email_verified_at)
                            <span class="badge-tw badge-success-tw text-xs py-1 px-2">{{ __('profile.verified') }}</span>
                        @else
                            <span class="badge-tw badge-warning-tw text-xs py-1 px-2">{{ __('profile.pending') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('frontend.common.footer')
