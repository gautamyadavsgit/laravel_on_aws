<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertySharesRequest extends FormRequest
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
            'equity_raise' => 'required|numeric',
            'price_per_share_deed' => 'required|numeric',
            'total_developer_share_deeds' => 'required|numeric',
            'total_raise_share_deeds' => 'required|numeric',
            'total_share_deeds' => 'required|numeric',
            'first_dividend_date' => 'nullable|date',
            'seccond_dividend_date' => 'nullable|date',
        ];
    }
}
