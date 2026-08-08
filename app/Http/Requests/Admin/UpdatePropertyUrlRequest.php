<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyUrlRequest extends FormRequest
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
            'google_map' => 'required|url',
            'zillow' => 'required|url',
            'airbnb' => 'required|url',
            'vrbo' => 'required|url',
            'alt_listing_1' => 'required|url',
            'alt_listing_2' => 'required|url',
            'alt_listing_3' => 'required|url',
        ];
    }
}
