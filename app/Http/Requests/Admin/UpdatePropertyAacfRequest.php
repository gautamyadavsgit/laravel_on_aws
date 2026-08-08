<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyAacfRequest extends FormRequest
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
            'annual_rent_amount' => 'required|numeric',
            'annual_rent_gross_yield' => 'required|numeric',
            'aacf_expences' => 'required|numeric',
            'aacf_net' => 'required|numeric',
        ];
    }
}
