<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gautam Real Estate - Premier Fractional Real Estate Investment Platform">
    <title>{{ $title ?? 'Gautam Real Estate - Fractional Real Estate Investments' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
    @vite(['resources/css/app.css', 'resources/js/frontend.js'])
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 min-h-screen flex flex-col antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Site Header & Navigation -->
    <header class="sticky top-0 z-50 backdrop-blur-md bg-white/90 dark:bg-slate-950/90 border-b border-slate-200/80 dark:border-slate-800/80 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-md shadow-indigo-500/20 group-hover:scale-105 transition duration-200">
                    <i class="bi bi-building-fill text-xl"></i>
                </div>
                <span class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Gautam<span class="text-indigo-600 dark:text-indigo-400">REI</span></span>
            </a>

            <!-- Desktop Nav Menu -->
            <nav class="hidden md:flex items-center gap-1">
                <a href="{{ url('/') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium transition {{ request()->is('/') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/70 dark:bg-indigo-950/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-800' }}">Home</a>
                <a href="{{ route('properties') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('properties') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/70 dark:bg-indigo-950/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-800' }}">Browse Investments</a>
                <a href="{{ url('property_singlepage') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium transition {{ request()->is('property*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/70 dark:bg-indigo-950/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-800' }}">Featured Asset</a>
                @guest
                    <a href="{{ route('register') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('register') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/70 dark:bg-indigo-950/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-800' }}">Investor Registration</a>
                @endguest
            </nav>

            <div class="flex items-center gap-2.5">
                <!-- Theme Switcher -->
                <button type="button" onclick="toggleTheme()" class="p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition" aria-label="Toggle Dark Mode" title="Toggle Theme">
                    <i class="bi bi-moon-stars-fill theme-toggle-icon text-slate-600 dark:text-amber-400 text-base"></i>
                </button>

                @auth
                    <!-- Authenticated User Menu -->
                    <div class="hidden sm:flex items-center gap-2">
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60 text-xs font-semibold text-slate-800 dark:text-slate-200">
                            <div class="w-6 h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center text-[10px] font-bold">
                                {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                            </div>
                            <span>{{ Auth::user()->first_name }}</span>
                            @if(Auth::user()->email_verified_at)
                                <span class="text-emerald-500" title="Verified Investor"><i class="bi bi-patch-check-fill"></i></span>
                            @else
                                <span class="text-amber-500" title="Email Unverified"><i class="bi bi-exclamation-circle-fill"></i></span>
                            @endif
                        </div>

                        <a href="{{ route('logout') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 text-xs font-semibold hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/40 dark:hover:text-rose-400 hover:border-rose-200 dark:hover:border-rose-800 transition" title="Sign Out">
                            <i class="bi bi-box-arrow-right"></i> Log Out
                        </a>
                    </div>
                @else
                    <!-- Guest Login / Register Buttons -->
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 text-sm font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-700 transition">
                        <i class="bi bi-box-arrow-in-right text-indigo-600 dark:text-indigo-400"></i> Log In
                    </a>

                    <a href="{{ route('register') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 active:bg-indigo-800 shadow-sm shadow-indigo-500/25 transition">
                        <i class="bi bi-person-plus"></i> Register
                    </a>
                @endauth

                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn" type="button" class="md:hidden p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800" aria-label="Toggle navigation">
                    <i class="bi bi-list text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Navigation -->
        <div id="mobileNavMenu" class="hidden md:hidden border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-4 space-y-2">
            <a href="{{ url('/') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 {{ request()->is('/') ? 'text-indigo-600 dark:text-indigo-400 font-semibold' : '' }}">Home</a>
            <a href="{{ route('properties') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 {{ request()->routeIs('properties') ? 'text-indigo-600 dark:text-indigo-400 font-semibold' : '' }}">Browse Investments</a>
            <a href="{{ url('property_singlepage') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 {{ request()->is('property*') ? 'text-indigo-600 dark:text-indigo-400 font-semibold' : '' }}">Featured Asset</a>
            
            @auth
                <div class="pt-3 border-t border-slate-100 dark:border-slate-800 space-y-2">
                    <div class="px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-900 text-xs font-semibold text-slate-700 dark:text-slate-300 flex items-center justify-between">
                        <span>Signed in as <strong>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</strong></span>
                        @if(Auth::user()->email_verified_at)
                            <span class="badge-tw badge-success-tw text-[10px]">Verified</span>
                        @else
                            <span class="badge-tw badge-warning-tw text-[10px]">Unverified</span>
                        @endif
                    </div>
                    <a href="{{ route('logout') }}" class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl border border-rose-200 dark:border-rose-900 text-rose-600 dark:text-rose-400 text-sm font-semibold hover:bg-rose-50 dark:hover:bg-rose-950/40">
                        <i class="bi bi-box-arrow-right"></i> Log Out
                    </a>
                </div>
            @else
                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 {{ request()->routeIs('register') ? 'text-indigo-600 dark:text-indigo-400 font-semibold' : '' }}">Investor Registration</a>
                
                <div class="pt-3 border-t border-slate-100 dark:border-slate-800 grid grid-cols-2 gap-2">
                    <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 text-sm font-semibold hover:bg-slate-100 dark:hover:bg-slate-800">
                        <i class="bi bi-box-arrow-in-right text-indigo-600"></i> Log In
                    </a>
                    <a href="{{ route('register') }}" class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-semibold shadow-sm hover:bg-indigo-700">
                        <i class="bi bi-person-plus"></i> Register
                    </a>
                </div>
            @endauth
        </div>
    </header>