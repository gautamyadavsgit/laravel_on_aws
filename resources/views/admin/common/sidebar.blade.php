<!-- Mobile Sidebar Backdrop Overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity"></div>

<!-- Admin Sidebar -->
<aside id="adminSidebar" class="fixed top-0 bottom-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col transition-transform duration-300 lg:translate-x-0 -translate-x-full lg:static lg:z-auto">
    <!-- Brand Logo -->
    <div class="h-16 flex items-center px-6 border-b border-slate-100 dark:border-slate-800/80">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-sm">
                <i class="bi bi-building-fill text-lg"></i>
            </div>
            <span class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Gautam<span class="text-indigo-600 dark:text-indigo-400">REI</span></span>
        </a>
    </div>

    <!-- Navigation Menu -->
    <div class="flex-1 overflow-y-auto px-4 py-6 space-y-6">
        <div>
            <span class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Main Menu</span>
            <div class="mt-2 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200' }}">
                    <i class="bi bi-grid-1x2 text-base"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>

        <div>
            <span class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Properties</span>
            <div class="mt-2 space-y-1">
                <a href="{{ route('manage-property.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('manage-property.index') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200' }}">
                    <i class="bi bi-building text-base"></i>
                    <span>View All Properties</span>
                </a>
                <a href="{{ route('manage-property.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('manage-property.create') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200' }}">
                    <i class="bi bi-plus-circle text-base"></i>
                    <span>Add New Property</span>
                </a>
            </div>
        </div>

        <div>
            <span class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Security</span>
            <div class="mt-2 space-y-1">
                <a href="{{ route('admin.logout') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/40 transition">
                    <i class="bi bi-box-arrow-right text-base"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
        <span>© {{ date('Y') }} Gautam REI</span>
        <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-mono text-[10px]">v2.0-tw</span>
    </div>
</aside>

<!-- Admin Main View Area -->
<div class="flex-1 flex flex-col min-w-0">
    <!-- Topbar -->
    <header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30">
        <div class="flex items-center gap-4">
            <button id="sidebarToggleBtn" type="button" class="lg:hidden p-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800" aria-label="Toggle Sidebar">
                <i class="bi bi-list text-xl"></i>
            </button>
            <span class="text-sm font-semibold text-slate-700 dark:text-slate-200 hidden sm:inline">{{ $pagetitle ?? 'Property Administration' }}</span>
        </div>

        <div class="flex items-center gap-3">
            <!-- Theme Toggle Button -->
            <button type="button" onclick="toggleTheme()" class="p-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition" aria-label="Toggle Dark Mode" title="Toggle Theme">
                <i class="bi bi-moon-stars-fill theme-toggle-icon text-slate-600 dark:text-amber-400 text-base"></i>
            </button>

            <!-- User Menu Dropdown -->
            <div class="relative">
                <button type="button" data-dropdown-toggle="adminProfileMenu" class="flex items-center gap-2.5 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition text-sm">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 flex items-center justify-center font-semibold">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <span class="font-medium text-slate-700 dark:text-slate-200 hidden md:inline">Administrator</span>
                    <i class="bi bi-chevron-down text-xs text-slate-400"></i>
                </button>

                <div id="adminProfileMenu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-slate-900 rounded-xl shadow-lg border border-slate-200 dark:border-slate-800 py-1.5 z-50">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="{{ route('manage-property.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                        <i class="bi bi-building"></i> Properties
                    </a>
                    <div class="my-1 border-t border-slate-100 dark:border-slate-800"></div>
                    <a href="{{ route('admin.logout') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-6">
        <!-- Flash Alerts & Session Feedback -->
        @if (session('success'))
            <div role="alert" class="alert-tw alert-success-tw">
                <i class="bi bi-check-circle-fill text-lg text-emerald-600 dark:text-emerald-400 shrink-0"></i>
                <div class="flex-1 font-medium">
                    {{ session('success') }}
                </div>
                <button type="button" data-dismiss-alert class="text-emerald-700 dark:text-emerald-300 hover:opacity-75" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div role="alert" class="alert-tw alert-danger-tw">
                <i class="bi bi-exclamation-triangle-fill text-lg text-rose-600 dark:text-rose-400 shrink-0"></i>
                <div class="flex-1 font-medium">
                    {{ session('error') }}
                </div>
                <button type="button" data-dismiss-alert class="text-rose-700 dark:text-rose-300 hover:opacity-75" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div role="alert" class="alert-tw alert-danger-tw">
                <i class="bi bi-x-circle-fill text-lg text-rose-600 dark:text-rose-400 shrink-0"></i>
                <div class="flex-1">
                    <strong class="font-semibold block mb-1">Please correct the following errors:</strong>
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" data-dismiss-alert class="text-rose-700 dark:text-rose-300 hover:opacity-75" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif
