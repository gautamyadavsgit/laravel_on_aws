@extends('admin.common.page')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
            <i class="bi bi-pencil-square text-indigo-600 dark:text-indigo-400"></i> Edit Primary Property Details
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Update main property listing information for #{{ $property->id }} - {{ $property->name }}.</p>
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
                <i class="bi bi-building text-indigo-600 dark:text-indigo-400"></i> Property Attributes
            </h2>
            <span class="badge-tw badge-info-tw">ID #{{ $property->id }}</span>
        </div>

        <form action="{{ route('manage-property.update', $property->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="availability">Availability</label>
                    <input class="input-tw" type="text" name="availability" id="availability" value="{{ $property->availability }}">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="name">Property Name</label>
                    <input class="input-tw" type="text" name="name" id="name" value="{{ $property->name }}">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="address1">Address Line 1</label>
                    <input class="input-tw" type="text" name="address1" id="address1" value="{{ $property->address1 }}">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="address2">Address Line 2</label>
                    <input class="input-tw" type="text" name="address2" id="address2" value="{{ $property->address2 }}">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="zoning">Zoning</label>
                    <input class="input-tw" type="text" name="zoning" id="zoning" value="{{ $property->zoning }}">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="management_company">Management Company</label>
                    <input class="input-tw" type="text" name="management_company" id="management_company" value="{{ $property->management_company }}">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="description">Description</label>
                    <textarea class="textarea-tw" name="description" id="description" rows="4">{{ $property->description }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="btn-primary-tw">
                    <i class="bi bi-check-lg"></i> Save Changes
                </button>
                <a href="{{ route('manage-property.index') }}" class="btn-secondary-tw">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection