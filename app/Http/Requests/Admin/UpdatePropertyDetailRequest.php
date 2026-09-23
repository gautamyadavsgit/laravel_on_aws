<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Bounds mirror the property_details column types (unsigned small int / int / decimal(8,2))
     * so out-of-range input is reported to the admin instead of failing at the database.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'max:255'],
            'bedrooms' => ['required', 'integer', 'min:0', 'max:1000'],
            'baths' => ['required', 'integer', 'min:0', 'max:1000'],
            'half_baths' => ['required', 'integer', 'min:0', 'max:1000'],
            'sleeps' => ['required', 'integer', 'min:0', 'max:1000'],
            'garages' => ['required', 'integer', 'min:0', 'max:1000'],
            'square_feets' => ['required', 'integer', 'min:0', 'max:100000000'],
            'stories' => ['required', 'string', 'max:255'],
            'units' => ['required', 'integer', 'min:1', 'max:10000'],
            'lot_size' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'year_built' => ['required', 'integer', 'digits:4', 'between:1800,'.((int) date('Y') + 5)],
            'zoning' => ['required', 'string', 'max:255'],
            'value' => ['required', 'integer', 'min:0', 'max:1000000000'],
        ];
    }
}
