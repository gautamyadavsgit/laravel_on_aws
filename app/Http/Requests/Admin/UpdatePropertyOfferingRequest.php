<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
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
     * The offering columns are unsigned whole-dollar integers.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $amount = ['required', 'integer', 'min:0', 'max:10000000000'];

        return [
            'offering_purchase' => $amount,
            'offering_build_cost' => $amount,
            'offering_improvements' => $amount,
            'offering_closing_cost' => $amount,
            'offering_sourcing_fees' => $amount,
        ];
    }
}
