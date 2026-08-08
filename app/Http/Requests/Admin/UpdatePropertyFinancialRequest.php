<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyFinancialRequest extends FormRequest
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
            'management_fee' => 'required|numeric',
            'cash_reserve' => 'required|numeric',
            'hold_period' => 'required|numeric',
            'annual_appreciation' => 'required|numeric',
            'aum_fee_1' => 'required|numeric',
            'aum_fee_2' => 'required|numeric',
            'aum_fee_3' => 'required|numeric',
            'average_time_to_rent' => 'required|numeric',
        ];
    }
}
