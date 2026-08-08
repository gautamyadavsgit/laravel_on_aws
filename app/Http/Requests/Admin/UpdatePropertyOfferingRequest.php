<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyOfferingRequest extends FormRequest
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
            'offering_purchase' => 'required|numeric',
            'offering_build_cost' => 'required|numeric',
            'offering_improvements' => 'required|numeric',
            'offering_closing_cost' => 'required|numeric',
            'offering_sourcing_fees' => 'required|numeric',
        ];
    }
}
