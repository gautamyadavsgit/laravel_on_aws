<?php

namespace App\Http\Requests\Admin;

use App\Enums\PropertyAvailability;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Create and update share one form; the only difference is that a new property needs at least one image.
 */
class PropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check();
    }

    /**
     * Bounds mirror the column types so out-of-range input is reported to the admin instead of failing at the database.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $amount = ['required', 'integer', 'min:0', 'max:10000000000'];
        $count = ['required', 'integer', 'min:0', 'max:1000'];

        return [
            'availability' => ['required', Rule::enum(PropertyAvailability::class)],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:20000'],
            'management_company' => ['required', 'string', 'max:255'],

            'address_1' => ['required', 'string', 'max:255'],
            'address_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:112'],
            'state' => ['required', 'string', 'max:112'],
            'zip' => ['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9][A-Za-z0-9\s\-]*$/'],

            'type' => ['required', 'string', 'max:255'],
            'bedrooms' => $count,
            'baths' => $count,
            'half_baths' => $count,
            'sleeps' => $count,
            'garages' => $count,
            'square_feets' => ['required', 'integer', 'min:0', 'max:100000000'],
            'stories' => ['required', 'string', 'max:255'],
            'units' => ['required', 'integer', 'min:1', 'max:10000'],
            'lot_size' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'year_built' => ['required', 'integer', 'digits:4', 'between:1800,'.((int) date('Y') + 5)],
            'zoning' => ['required', 'string', 'max:255'],
            'value' => ['required', 'integer', 'min:0', 'max:1000000000'],

            'offering_purchase' => $amount,
            'offering_build_cost' => $amount,
            'offering_improvements' => $amount,
            'offering_closing_cost' => $amount,
            'offering_sourcing_fees' => $amount,

            'property_images' => $this->isMethod('POST') ? ['required', 'array', 'min:1', 'max:20'] : ['nullable', 'array', 'max:20'],
            'property_images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ];
    }

    /**
     * Validated input that belongs on the properties row (everything except the uploaded files).
     *
     * @return array<string, mixed>
     */
    public function propertyAttributes(): array
    {
        return Arr::except($this->validated(), ['property_images']);
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'zip.regex' => 'The zip code may only contain letters, numbers, spaces and hyphens.',
        ];
    }
}
