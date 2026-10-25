@extends('admin.common.page')

@section('content')
<div class="max-w-5xl mx-auto w-full">
    @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 dark:bg-emerald-950/40 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2.5">
                <i class="bi bi-check-circle-fill text-emerald-600 dark:text-emerald-400 text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-200">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    <div class="mb-6 card-tw p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 border-l-4 border-l-indigo-600 dark:border-l-indigo-500">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Edit {{ $property->name }}</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">Update listing details, location, specifications, offering and photos.</p>
        </div>
        <a href="{{ route('admin.properties.index') }}" class="btn-secondary-tw text-xs py-2 px-3 flex items-center gap-1.5">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Properties</span>
        </a>
    </div>

    <div class="card-tw shadow-sm">
        @include('admin.properties.partials.form', ['property' => $property])
    </div>
</div>
@endsection
