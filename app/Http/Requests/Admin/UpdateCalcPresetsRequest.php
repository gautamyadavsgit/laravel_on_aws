<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCalcPresetsRequest extends FormRequest
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
            'calc_preset_1' => 'nullable|numeric',
            'calc_preset_2' => 'nullable|numeric',
            'calc_preset_3' => 'nullable|numeric',
            'calc_preset_4' => 'nullable|numeric',
            'calc_preset_5' => 'nullable|numeric',
            'calc_preset_6' => 'nullable|numeric',
        ];
    }
}
