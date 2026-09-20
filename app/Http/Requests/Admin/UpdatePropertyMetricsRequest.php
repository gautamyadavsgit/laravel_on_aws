<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyMetricsRequest extends FormRequest
{
    /**
     * Largest value the decimal(12,2) money columns can hold.
     */
    protected const MAX_MONEY = 9999999999.99;

    /**
     * Largest value the decimal(14,2) projected valuation columns can hold.
     */
    protected const MAX_PROJECTED = 999999999999.99;

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'gross_annual_rent' => ['nullable', 'numeric', 'min:0', 'max:'.self::MAX_MONEY],
            'operating_expenses' => ['nullable', 'numeric', 'min:0', 'max:'.self::MAX_MONEY],
            'net_operating_income' => ['nullable', 'numeric', 'min:0', 'max:'.self::MAX_MONEY],
            'cap_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'annual_cash_flow' => ['nullable', 'numeric', 'min:0', 'max:'.self::MAX_MONEY],
            'cash_on_cash_return' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'projected_irr' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'estimated_appreciation_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'projected_value_5yr' => ['nullable', 'numeric', 'min:0', 'max:'.self::MAX_PROJECTED],
            'projected_value_10yr' => ['nullable', 'numeric', 'min:0', 'max:'.self::MAX_PROJECTED],
            'annual_depreciation_deduction' => ['nullable', 'numeric', 'min:0', 'max:'.self::MAX_MONEY],
            'tax_savings_estimate' => ['nullable', 'numeric', 'min:0', 'max:'.self::MAX_MONEY],
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
