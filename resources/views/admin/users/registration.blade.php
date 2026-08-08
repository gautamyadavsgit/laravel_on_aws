@extends('admin.common.page')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
            <i class="bi bi-people text-indigo-600 dark:text-indigo-400"></i> Investor Registrations
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Accredited investor verifications, KYC/AML review, and user management.</p>
    </div>
</div>

<div class="card-tw text-center py-12">
    <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-3xl mx-auto mb-4">
        <i class="bi bi-person-badge"></i>
    </div>
    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Investor Accreditation Pipeline</h2>
    <p class="text-sm text-slate-500 dark:text-slate-400 max-w-md mx-auto mt-1 mb-6">
        KYC/AML verification status and accredited investor compliance review.
    </p>
    <a href="{{ route('manage-property.index') }}" class="btn-primary-tw">
        <i class="bi bi-building"></i> View Properties
    </a>
</div>
@endsection
