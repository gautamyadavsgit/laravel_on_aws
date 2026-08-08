<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyDocumentRequest extends FormRequest
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
            'document_1' => 'nullable|mimes:pdf|max:2048',
            'document_2' => 'nullable|mimes:pdf|max:2048',
            'document_3' => 'nullable|mimes:pdf|max:2048',
            'document_4' => 'nullable|mimes:pdf|max:2048',
            'document_5' => 'nullable|mimes:pdf|max:2048',
            'Documents_Expence_Calculations' => 'nullable|mimes:pdf|max:2048',
            'Documents_Rent_Calculations' => 'nullable|mimes:pdf|max:2048',
            'Documents_Deed_Restrictions' => 'nullable|mimes:pdf|max:2048',
            'Documents_Deed_Example' => 'nullable|mimes:pdf|max:2048',
            'Documents_Closing_Statement_Example' => 'nullable|mimes:pdf|max:2048',
            'Documents_Master_Deed' => 'nullable|mimes:pdf|max:2048',

        ];
    }
}
