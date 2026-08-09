@extends('admin.common.page')

@section('content')
<div class="max-w-5xl mx-auto w-full">
    <!-- Shared Stepper Header -->
    @include('admin.properties.partials.nav')

    <!-- Flash Messages -->
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

    <div class="card-tw shadow-sm">
        <div class="card-header-tw flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-indigo-600/10 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold">
                    <i class="bi bi-file-earmark-pdf"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">
                        Stage 7: Legal Documentation & Financial Disclosures
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Upload operating agreements, pro-forma calculations, deed restrictions, and title certificates.</p>
                </div>
            </div>
            <span class="badge-tw badge-info-tw hidden sm:inline-flex">Step 7 of 8</span>
        </div>

        <form action="{{ route('admin.manage-property.update-property-documents', ['id' => $property_id]) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Upload Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- 1. Master Deed / Title -->
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 space-y-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300" for="Documents_Master_Deed">
                        Master Deed & Fractional Title (PDF)
                    </label>
                    <input type="file" class="input-tw file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" accept="application/pdf" name="Documents_Master_Deed" id="Documents_Master_Deed">
                    <p class="text-[11px] text-slate-400">Master deed establishing fractional ownership rights.</p>
                </div>

                <!-- 2. Expense Calculations -->
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 space-y-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300" for="Documents_Expence_Calculations">
                        Expense Statement & Escrow (PDF)
                    </label>
                    <input type="file" class="input-tw file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" accept="application/pdf" name="Documents_Expence_Calculations" id="Documents_Expence_Calculations">
                    <p class="text-[11px] text-slate-400">Detailed line-item maintenance, insurance, and HOA.</p>
                </div>

                <!-- 3. Rent Calculations -->
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 space-y-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300" for="Documents_Rent_Calculations">
                        Rental Yield & Pro-Forma (PDF)
                    </label>
                    <input type="file" class="input-tw file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" accept="application/pdf" name="Documents_Rent_Calculations" id="Documents_Rent_Calculations">
                    <p class="text-[11px] text-slate-400">Projected ADR, historical occupancy, and seasonal rates.</p>
                </div>

                <!-- 4. Operating Agreement -->
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 space-y-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300" for="document_1">
                        LLC Operating Agreement (PDF)
                    </label>
                    <input type="file" class="input-tw file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" accept="application/pdf" name="document_1" id="document_1">
                    <p class="text-[11px] text-slate-400">Property-holding SPV LLC governing documents.</p>
                </div>

                <!-- 5. Closing Statement -->
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 space-y-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300" for="Documents_Closing_Statement_Example">
                        Closing Statement Sample (PDF)
                    </label>
                    <input type="file" class="input-tw file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" accept="application/pdf" name="Documents_Closing_Statement_Example" id="Documents_Closing_Statement_Example">
                    <p class="text-[11px] text-slate-400">HUD-1/Settlement statement closing audit.</p>
                </div>

                <!-- 6. Deed Restrictions -->
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 space-y-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300" for="Documents_Deed_Restrictions">
                        Deed Restrictions & HOA (PDF)
                    </label>
                    <input type="file" class="input-tw file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950 dark:file:text-indigo-300" accept="application/pdf" name="Documents_Deed_Restrictions" id="Documents_Deed_Restrictions">
                    <p class="text-[11px] text-slate-400">CC&Rs, bylaws, and rental governance.</p>
                </div>
            </div>

            <!-- Complete Action Buttons -->
            <div class="border-t border-slate-200 dark:border-slate-800 pt-5 flex flex-col sm:flex-row items-center justify-between gap-3">
                <a href="{{ route('admin.manage-property.edit-property-offerings', ['id' => $property_id]) }}" class="btn-secondary-tw w-full sm:w-auto text-center order-2 sm:order-1">
                    <i class="bi bi-arrow-left"></i> Previous Step
                </a>
                <div class="flex items-center gap-2.5 w-full sm:w-auto order-1 sm:order-2">
                    <button type="submit" class="btn-primary-tw w-full sm:w-auto flex items-center justify-center gap-2">
                        <i class="bi bi-check-lg text-base"></i> Save & Continue to Underwriting & Metrics
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
