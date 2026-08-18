@include('frontend.common.header', ['title' => 'Investor Login | Gautam Real Estate'])

<div class="py-14 sm:py-20 flex-1 flex items-center justify-center">
    <div class="max-w-md w-full mx-auto px-4 sm:px-6">
        <div class="card-tw p-8 sm:p-10 space-y-6 shadow-xl relative overflow-hidden">
            <!-- Header section -->
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600/10 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 mx-auto flex items-center justify-center text-2xl mb-3 shadow-inner">
                    <i class="bi bi-box-arrow-in-right"></i>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ __('auth.login_title') }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('auth.login_description') }}</p>
            </div>

            <!-- Flash Success Message -->
            @if (session('success'))
                <div role="alert" class="alert-tw alert-success-tw">
                    <i class="bi bi-check-circle-fill text-lg text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5"></i>
                    <div class="flex-1 text-sm font-medium">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- Flash Status Message (e.g. Password reset link sent) -->
            @if (session('status'))
                <div role="alert" class="alert-tw alert-success-tw">
                    <i class="bi bi-info-circle-fill text-lg text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5"></i>
                    <div class="flex-1 text-sm font-medium">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            <!-- Flash Error Message -->
            @if (session('error'))
                <div role="alert" class="alert-tw alert-danger-tw">
                    <i class="bi bi-exclamation-triangle-fill text-lg text-rose-600 dark:text-rose-400 shrink-0 mt-0.5"></i>
                    <div class="flex-1 text-sm font-medium">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Form Validation Errors -->
            @if ($errors->any())
                <div role="alert" class="alert-tw alert-danger-tw">
                    <i class="bi bi-x-circle-fill text-lg text-rose-600 dark:text-rose-400 shrink-0 mt-0.5"></i>
                    <div class="flex-1 text-sm">
                        <strong class="font-semibold block mb-1">{{ __('messages.login_failed') }}</strong>
                        <ul class="list-disc list-inside space-y-0.5 text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4" id="loginForm">
                @csrf
                <input type="hidden" name="redirect" value="{{ request('redirect', 'properties') }}">

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="email">
                        {{ __('profile.email_address') }} <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="email" class="input-tw pl-10" name="email" id="email" value="{{ old('email') }}" required autofocus placeholder="investor@example.com">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-envelope"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300" for="password">
                            {{ __('auth.password') }} <span class="text-rose-500">*</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 hover:underline">
                            {{ __('auth.forgot_password') }}
                        </a>
                    </div>
                    <div class="relative">
                        <input type="password" class="input-tw pl-10 pr-10" name="password" id="password" required placeholder="••••••••">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-lock"></i>
                        </div>
                        <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200" aria-label="Toggle password visibility">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2.5 text-xs text-slate-600 dark:text-slate-400 cursor-pointer select-none">
                        <input type="checkbox" name="remember" id="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800">
                        <span>{{ __('auth.remember_me') }}</span>
                    </label>
                </div>

                <button type="submit" class="btn-primary-tw w-full py-3 shadow-md shadow-indigo-500/20 text-sm font-semibold" id="submitBtn">
                    <i class="bi bi-box-arrow-in-right"></i> {{ __('auth.sign_in') }}
                </button>
            </form>

            <!-- Register Alternative -->
            <div class="text-center pt-3 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-400">
                {{ __('auth.new_to_platform') }}
                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 hover:underline ml-1">
                    {{ __('auth.create_account') }} &rarr;
                </a>
            </div>

            <!-- Admin Login Link -->
            <div class="text-center text-[11px] text-slate-400 dark:text-slate-500">
                {{ __('auth.admin_question') }} <a href="{{ route('admin.index') }}" class="hover:underline text-slate-500 dark:text-slate-400">{{ __('auth.admin_portal') }}</a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

document.getElementById('loginForm')?.addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="inline-block animate-spin mr-2"><i class="bi bi-arrow-repeat"></i></span> {{ __('auth.signing_in') }}';
    }
});
</script>

@include('frontend.common.footer')
