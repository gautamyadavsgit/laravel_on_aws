<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Admin Login | Gautam Real Estate</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gautam Real Estate Admin Login" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Zero FOUC Dark Mode Initializer -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Vite Tailwind CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/admin.js'])
</head>
<body class="bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 min-h-screen flex items-center justify-center p-4 antialiased relative">

    <!-- Theme Switcher Floating Action -->
    <div class="absolute top-6 right-6">
        <button type="button" onclick="toggleTheme()" class="p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 shadow-sm transition" title="Toggle Theme">
            <i class="bi bi-moon-stars-fill theme-toggle-icon text-slate-600 dark:text-amber-400 text-base"></i>
        </button>
    </div>

    <div class="w-full max-w-md">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl p-8 space-y-6">
            <div class="text-center space-y-2">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-md">
                        <i class="bi bi-building-fill text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Gautam<span class="text-indigo-600 dark:text-indigo-400">REI</span></span>
                </a>
                <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white pt-2">Welcome Back</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Sign in to access your administrative dashboard.</p>
            </div>

            @if (session('error'))
                <div role="alert" class="alert-tw alert-danger-tw">
                    <i class="bi bi-exclamation-triangle-fill text-lg text-rose-600 dark:text-rose-400 shrink-0"></i>
                    <div class="flex-1 text-sm">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div role="alert" class="alert-tw alert-danger-tw">
                    <i class="bi bi-x-circle-fill text-lg text-rose-600 dark:text-rose-400 shrink-0"></i>
                    <div class="flex-1 text-sm">
                        <strong class="font-semibold block mb-1">Login Failed</strong>
                        <ul class="list-disc list-inside space-y-0.5 text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="username">Email or Username</label>
                    <input type="text" class="input-tw" name="email" id="username" value="{{ old('email') }}" placeholder="admin@example.com" required autofocus>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300" for="userpassword">Password</label>
                    </div>
                    <input type="password" class="input-tw" name="password" id="userpassword" placeholder="••••••••" required>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400 cursor-pointer">
                        <input type="checkbox" id="rememberMe" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800">
                        <span>Remember me</span>
                    </label>
                </div>

                <button type="submit" class="btn-primary-tw w-full py-2.5">
                    <i class="bi bi-box-arrow-in-right"></i> Sign In
                </button>
            </form>

            <div class="text-center text-xs text-slate-400 dark:text-slate-500 pt-2 border-t border-slate-100 dark:border-slate-800">
                © {{ date('Y') }} Gautam Real Estate. Clean & Secure.
            </div>
        </div>
    </div>
</body>
</html>