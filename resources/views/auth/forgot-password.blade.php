@include('frontend.common.header', ['title' => 'Forgot Password | Gautam Real Estate'])

<div class="py-14 sm:py-20 flex-1 flex items-center justify-center">
    <div class="max-w-md w-full mx-auto px-4 sm:px-6">
        <div class="card-tw p-8 sm:p-10 space-y-6 shadow-xl relative overflow-hidden">
            <!-- Header section -->
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600/10 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 mx-auto flex items-center justify-center text-2xl mb-3 shadow-inner">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Reset Password</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Enter your email address and we'll send you a link to reset your account password.</p>
            </div>

            <!-- Flash Success / Status Message -->
            @if (session('status'))
                <div role="alert" class="alert-tw alert-success-tw">
                    <i class="bi bi-check-circle-fill text-lg text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5"></i>
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
                        <strong class="font-semibold block mb-1">Request Failed</strong>
                        <ul class="list-disc list-inside space-y-0.5 text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Forgot Password Form -->
            <form action="{{ route('password.email') }}" method="POST" class="space-y-4" id="forgotForm">
                @csrf

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="email">
                        Account Email Address <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="email" class="input-tw pl-10" name="email" id="email" value="{{ old('email') }}" required autofocus placeholder="investor@example.com">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-envelope"></i>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-primary-tw w-full py-3 shadow-md shadow-indigo-500/20 text-sm font-semibold" id="submitBtn">
                    <i class="bi bi-send"></i> Send Password Reset Link
                </button>
            </form>

            <!-- Back to login link -->
            <div class="text-center pt-3 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-400">
                Remembered your password?
                <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 hover:underline ml-1">
                    &larr; Back to sign in
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('forgotForm')?.addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="inline-block animate-spin mr-2"><i class="bi bi-arrow-repeat"></i></span> Sending Link...';
    }
});
</script>

@include('frontend.common.footer')
