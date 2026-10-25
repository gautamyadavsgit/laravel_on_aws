@extends('admin.common.page')

@section('content')
<div class="max-w-5xl mx-auto w-full">
    <div class="mb-6 card-tw p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 border-l-4 border-l-indigo-600 dark:border-l-indigo-500">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-xl bg-indigo-600/10 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl shrink-0 font-bold">
                <i class="bi bi-plus-circle"></i>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Create New Property</h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    Enter the property information to initialize a new fractional real estate investment offering.
                </p>
            </div>
        </div>
        <a href="{{ route('admin.properties.index') }}" class="btn-secondary-tw text-xs py-2 px-3 flex items-center gap-1.5">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Properties</span>
        </a>
    </div>

    <div class="card-tw shadow-sm">
        @include('admin.properties.partials.form', ['property' => new \App\Models\Property])
    </div>
</div>
@endsection
