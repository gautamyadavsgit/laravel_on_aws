@extends('admin.common.page')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
            <i class="bi bi-file-earmark-pdf text-indigo-600 dark:text-indigo-400"></i> Edit Legal & Financial Documents
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Upload PDF operating agreements, deeds, and pro-forma statements for property #{{ $property_id }}.</p>
    </div>
    <div>
        <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw">
            <i class="bi bi-arrow-left"></i> Back to Properties
        </a>
    </div>
</div>

<div class="max-w-4xl mx-auto w-full">
    <div class="card-tw">
        <div class="card-header-tw">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-folder2-open text-indigo-600 dark:text-indigo-400"></i> Legal Documentation & Statements
            </h2>
            <span class="badge-tw badge-info-tw">Property ID #{{ $property_id }}</span>
        </div>

        <form action="{{ route('admin.manage-property.update-property-documents', ['id' => $property_id]) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @for ($i = 1; $i <= 5; $i++)
                    @php
                        $documentKey = 'document_' . $i;
                    @endphp
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="{{ $documentKey }}">
                            Legal Document Tier {{ $i }} (PDF)
                        </label>
                        <input type="file" class="input-tw file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" accept="application/pdf" name="{{ $documentKey }}" id="{{ $documentKey }}">
                    </div>
                @endfor

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="Documents_Expence_Calculations">
                        Expense Calculation (PDF)
                    </label>
                    <input type="file" class="input-tw file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" accept="application/pdf" name="Documents_Expence_Calculations" id="Documents_Expence_Calculations">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="Documents_Rent_Calculations">
                        Rent Calculation (PDF)
                    </label>
                    <input type="file" class="input-tw file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" accept="application/pdf" name="Documents_Rent_Calculations" id="Documents_Rent_Calculations">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="Documents_Deed_Restrictions">
                        Deed Restrictions (PDF)
                    </label>
                    <input type="file" class="input-tw file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" accept="application/pdf" name="Documents_Deed_Restrictions" id="Documents_Deed_Restrictions">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="Documents_Deed_Example">
                        Specimen Deed Example (PDF)
                    </label>
                    <input type="file" class="input-tw file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" accept="application/pdf" name="Documents_Deed_Example" id="Documents_Deed_Example">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="Documents_Closing_Statement_Example">
                        Closing Statement (PDF)
                    </label>
                    <input type="file" class="input-tw file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" accept="application/pdf" name="Documents_Closing_Statement_Example" id="Documents_Closing_Statement_Example">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="Documents_Master_Deed">
                        Master Deed Agreement (PDF)
                    </label>
                    <input type="file" class="input-tw file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" accept="application/pdf" name="Documents_Master_Deed" id="Documents_Master_Deed">
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="btn-primary-tw">
                    <i class="bi bi-check-lg"></i> Upload & Update Documents
                </button>
                <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
