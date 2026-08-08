<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyDetailsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required',
            'bedrooms' => 'required|integer',
            'baths' => 'required|integer',
            'half_baths' => 'required|integer',
            'sleeps' => 'required|integer',
            'garages' => 'required|integer',
            'square_feets' => 'required|integer',
            'stories' => 'required|string',
            'units' => 'required|integer',
            'lot_size' => 'required|integer',
            'year_built' => 'required|integer|digits:4',
            'zoning' => 'required',
            'value' => 'required|integer',
        ];
    }
}
