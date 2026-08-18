@include('frontend.common.header', ['title' => 'Investment Interest Received | Gautam Real Estate'])

<div class="py-14 sm:py-20 flex-1 flex items-center justify-center">
    <div class="max-w-xl w-full mx-auto px-4 sm:px-6">
        <div class="card-tw p-8 sm:p-10 text-center shadow-xl border border-emerald-100 dark:border-emerald-900/40">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                <i class="bi bi-check-circle-fill text-3xl"></i>
            </div>

            <div class="mt-6 space-y-3">
                <span class="badge-tw badge-success-tw text-[10px] tracking-[0.2em] uppercase">{{ __('messages.request_submitted') }}</span>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ __('messages.investment_thank_you') }}</h1>
                <p class="text-base text-slate-600 dark:text-slate-300 leading-relaxed">
                    {{ __('messages.investment_request_received') }}
                </p>
            </div>

            <div class="mt-8 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 p-5 text-left">
                <div class="flex items-start gap-3">
                    <i class="bi bi-clock-history text-indigo-600 dark:text-indigo-400 text-lg mt-0.5"></i>
                    <div>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('messages.what_happens_next') }}</p>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                            {{ __('messages.what_happens_next_desc') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('properties') }}" class="btn-primary-tw px-6 py-3 text-sm font-semibold shadow-md">
                    <i class="bi bi-house-door"></i> {{ __('common.browse_investments') }}
                </a>
                <a href="{{ route('home') }}" class="btn-secondary-tw px-6 py-3 text-sm font-semibold">
                    <i class="bi bi-arrow-left"></i> {{ __('common.back_to_home') }}
                </a>
            </div>
        </div>
    </div>
</div>

@include('frontend.common.footer')
