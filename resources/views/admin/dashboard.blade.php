@extends('admin.common.page')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
            <i class="bi bi-speedometer2 text-indigo-600 dark:text-indigo-400"></i> Dashboard Overview
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Welcome to the Gautam Real Estate portfolio and property management system.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('manage-property.create') }}" class="btn-primary-tw">
            <i class="bi bi-plus-lg"></i> Add New Property
        </a>
        <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw">
            <i class="bi bi-building"></i> View All Properties
        </a>
    </div>
</div>

<!-- Stat Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    <div class="card-tw flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl shrink-0">
            <i class="bi bi-building-check"></i>
        </div>
        <div>
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Active Portfolio</div>
            <div class="text-xl font-bold text-slate-900 dark:text-white">Live</div>
            <div class="text-xs font-medium text-emerald-600 dark:text-emerald-400 flex items-center gap-1 mt-0.5">
                <i class="bi bi-arrow-up-short"></i> Managed Real Estate
            </div>
        </div>
    </div>

    <div class="card-tw flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl shrink-0">
            <i class="bi bi-houses"></i>
        </div>
        <div>
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Total Properties</div>
            <div class="text-xl font-bold text-slate-900 dark:text-white">{{ \App\Models\PropertyModel::count() }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Listed on Platform</div>
        </div>
    </div>

    <div class="card-tw flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 flex items-center justify-center text-xl shrink-0">
            <i class="bi bi-shield-check"></i>
        </div>
        <div>
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">System Health</div>
            <div class="text-xl font-bold text-slate-900 dark:text-white">Optimal</div>
            <div class="text-xs text-indigo-600 dark:text-indigo-400 font-medium mt-0.5">Tailwind CSS Engine</div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <div class="lg:col-span-8">
        <div class="card-tw">
            <div class="card-header-tw">
                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-grid text-indigo-600 dark:text-indigo-400"></i> Quick Navigation
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm">Manage Properties</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">View, edit, configure sub-details & delete properties.</p>
                    </div>
                    <a href="{{ route('manage-property.index') }}" class="btn-primary-tw text-xs py-2 px-3 shrink-0">
                        Open <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm">Add Property</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Create new fractional investment property listing.</p>
                    </div>
                    <a href="{{ route('manage-property.create') }}" class="btn-primary-tw text-xs py-2 px-3 shrink-0">
                        Create <i class="bi bi-plus-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-4">
        <div class="card-tw">
            <div class="card-header-tw">
                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-info-circle text-sky-600 dark:text-sky-400"></i> Architecture Status
                </h2>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                <div class="flex items-center justify-between py-3">
                    <span class="text-slate-500 dark:text-slate-400 flex items-center gap-2">
                        <i class="bi bi-check-circle-fill text-emerald-500 text-xs"></i> UI Styling
                    </span>
                    <span class="badge-tw badge-info-tw">Tailwind CSS 3.4</span>
                </div>
                <div class="flex items-center justify-between py-3">
                    <span class="text-slate-500 dark:text-slate-400 flex items-center gap-2">
                        <i class="bi bi-check-circle-fill text-emerald-500 text-xs"></i> Theme Engine
                    </span>
                    <span class="badge-tw badge-success-tw">Class Dark Mode</span>
                </div>
                <div class="flex items-center justify-between py-3">
                    <span class="text-slate-500 dark:text-slate-400 flex items-center gap-2">
                        <i class="bi bi-check-circle-fill text-emerald-500 text-xs"></i> Bootstrap
                    </span>
                    <span class="badge-tw badge-danger-tw">0% Dependency</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
