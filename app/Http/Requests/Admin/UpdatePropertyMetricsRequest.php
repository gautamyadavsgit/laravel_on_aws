<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyMetricsRequest extends FormRequest
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
            'gross_annual_rent' => ['nullable', 'numeric', 'min:0'],
            'operating_expenses' => ['nullable', 'numeric', 'min:0'],
            'net_operating_income' => ['nullable', 'numeric', 'min:0'],
            'cap_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'annual_cash_flow' => ['nullable', 'numeric', 'min:0'],
            'cash_on_cash_return' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'projected_irr' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'estimated_appreciation_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'projected_value_5yr' => ['nullable', 'numeric', 'min:0'],
            'projected_value_10yr' => ['nullable', 'numeric', 'min:0'],
            'annual_depreciation_deduction' => ['nullable', 'numeric', 'min:0'],
            'tax_savings_estimate' => ['nullable', 'numeric', 'min:0'],
            'is_1031_exchange_eligible' => ['nullable', 'boolean'],
            'cost_segregation_eligible' => ['nullable', 'boolean'],
            'diversification_score' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'occupancy_rate_projected' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'cash_flow_rating' => ['nullable', 'integer', 'min:0', 'max:100'],
            'appreciation_rating' => ['nullable', 'integer', 'min:0', 'max:100'],
            'tax_benefit_rating' => ['nullable', 'integer', 'min:0', 'max:100'],
            'diversification_rating' => ['nullable', 'integer', 'min:0', 'max:100'],
            'calculation_notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
