<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyExtraDetailsRequest extends FormRequest
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
            'deed_fraction_1' => 'required|string',
            'deed_fraction_2' => 'required|string',
            'leveraged' => 'string',
            'leverage_amount' => 'required_if:leveraged,1|numeric',
            'leverage_percent' => 'required_if:leveraged,1|numeric',
            'rent_rate' => 'required|numeric',
            'market_rent_rate' => 'required|numeric',
            'occupancy_rate' => 'required|numeric',
            'occupancy_status' => 'required',
        ];
    }
}
