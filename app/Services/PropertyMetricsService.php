<?php

namespace App\Services;

use App\Models\PropertyMetrics;
use App\Models\PropertyModel;

class PropertyMetricsService
{
    /**
     * Compute full underwriting metrics for a property based on its value and financial profile.
     */
    public static function computeDefaults(PropertyModel $property, array $overrides = []): array
    {
        $property->loadMissing(['propertyDetails', 'propertyOffering', 'propertyAddress']);

        $valuation = (float) ($property->propertyDetails->value
            ?? $property->propertyOffering->offering_purchase
            ?? 600000);

        if ($valuation <= 0) {
            $valuation = 600000;
        }

        // 1. Gross Annual Rent: ~11.2% for high-demand vacation fractional rentals
        $grossRent = isset($overrides['gross_annual_rent']) && $overrides['gross_annual_rent'] !== ''
            ? (float) $overrides['gross_annual_rent']
            : round($valuation * 0.112, 2);

        // 2. Operating Expenses: ~23% of gross rent (management, cleaning, insurance, repairs)
        $expenses = isset($overrides['operating_expenses']) && $overrides['operating_expenses'] !== ''
            ? (float) $overrides['operating_expenses']
            : round($grossRent * 0.23, 2);

        // 3. NOI (Net Operating Income)
        $noi = max(0, $grossRent - $expenses);

        // 4. Cap Rate (%)
        $capRate = isset($overrides['cap_rate']) && $overrides['cap_rate'] !== ''
            ? (float) $overrides['cap_rate']
            : round(($noi / $valuation) * 100, 2);

        // 5. Net Annual Cash Flow (after capital reserve allocation)
        $capitalReserve = round($grossRent * 0.03, 2);
        $annualCashFlow = isset($overrides['annual_cash_flow']) && $overrides['annual_cash_flow'] !== ''
            ? (float) $overrides['annual_cash_flow']
            : max(0, $noi - $capitalReserve);

        // 6. Cash on Cash Return (%)
        $cashOnCash = isset($overrides['cash_on_cash_return']) && $overrides['cash_on_cash_return'] !== ''
            ? (float) $overrides['cash_on_cash_return']
            : round(($annualCashFlow / $valuation) * 100, 2);

        // 7. Annual Appreciation Rate (%)
        $appreciationRate = isset($overrides['estimated_appreciation_rate']) && $overrides['estimated_appreciation_rate'] !== ''
            ? (float) $overrides['estimated_appreciation_rate']
            : 5.20;

        // 8. Projected 5-Year & 10-Year Valuations: Compound Growth V = P * (1 + r)^t
        $projVal5 = isset($overrides['projected_value_5yr']) && $overrides['projected_value_5yr'] !== ''
            ? (float) $overrides['projected_value_5yr']
            : round($valuation * pow(1 + ($appreciationRate / 100), 5), 2);

        $projVal10 = isset($overrides['projected_value_10yr']) && $overrides['projected_value_10yr'] !== ''
            ? (float) $overrides['projected_value_10yr']
            : round($valuation * pow(1 + ($appreciationRate / 100), 10), 2);

        // 9. Projected IRR (Internal Rate of Return %): Cap Rate + Appreciation Rate
        $projectedIrr = isset($overrides['projected_irr']) && $overrides['projected_irr'] !== ''
            ? (float) $overrides['projected_irr']
            : round($capRate + $appreciationRate + 0.8, 2);

        // 10. Tax Depreciation: Standard US Residential Rental 27.5-year straight line on 85% building basis
        $depreciableBasis = $valuation * 0.85;
        $annualDepreciation = isset($overrides['annual_depreciation_deduction']) && $overrides['annual_depreciation_deduction'] !== ''
            ? (float) $overrides['annual_depreciation_deduction']
            : round($depreciableBasis / 27.5, 2);

        // 11. Estimated Tax Savings (assuming 32% investor marginal rate)
        $taxSavings = isset($overrides['tax_savings_estimate']) && $overrides['tax_savings_estimate'] !== ''
            ? (float) $overrides['tax_savings_estimate']
            : round($annualDepreciation * 0.32, 2);

        // 12. Diversification & Occupancy
        $occupancyRate = isset($overrides['occupancy_rate_projected']) && $overrides['occupancy_rate_projected'] !== ''
            ? (float) $overrides['occupancy_rate_projected']
            : 86.50;

        $diversificationScore = isset($overrides['diversification_score']) && $overrides['diversification_score'] !== ''
            ? (float) $overrides['diversification_score']
            : 8.8;

        // 13. Goal Compatibility Scores (0 - 100)
        $cashFlowRating = isset($overrides['cash_flow_rating']) && $overrides['cash_flow_rating'] !== ''
            ? (int) $overrides['cash_flow_rating']
            : min(100, (int) round(($capRate / 12) * 100));

        $appreciationRating = isset($overrides['appreciation_rating']) && $overrides['appreciation_rating'] !== ''
            ? (int) $overrides['appreciation_rating']
            : min(100, (int) round(($appreciationRate / 6.5) * 100));

        $taxBenefitRating = isset($overrides['tax_benefit_rating']) && $overrides['tax_benefit_rating'] !== ''
            ? (int) $overrides['tax_benefit_rating']
            : 92;

        $diversificationRating = isset($overrides['diversification_rating']) && $overrides['diversification_rating'] !== ''
            ? (int) $overrides['diversification_rating']
            : (int) round($diversificationScore * 10);

        return [
            'gross_annual_rent' => $grossRent,
            'operating_expenses' => $expenses,
            'net_operating_income' => $noi,
            'cap_rate' => $capRate,
            'annual_cash_flow' => $annualCashFlow,
            'cash_on_cash_return' => $cashOnCash,
            'projected_irr' => $projectedIrr,
            'estimated_appreciation_rate' => $appreciationRate,
            'projected_value_5yr' => $projVal5,
            'projected_value_10yr' => $projVal10,
            'annual_depreciation_deduction' => $annualDepreciation,
            'tax_savings_estimate' => $taxSavings,
            'is_1031_exchange_eligible' => isset($overrides['is_1031_exchange_eligible']) ? (bool) $overrides['is_1031_exchange_eligible'] : true,
            'cost_segregation_eligible' => isset($overrides['cost_segregation_eligible']) ? (bool) $overrides['cost_segregation_eligible'] : true,
            'diversification_score' => $diversificationScore,
            'occupancy_rate_projected' => $occupancyRate,
            'cash_flow_rating' => $cashFlowRating,
            'appreciation_rating' => $appreciationRating,
            'tax_benefit_rating' => $taxBenefitRating,
            'diversification_rating' => $diversificationRating,
            'calculation_notes' => $overrides['calculation_notes'] ?? 'Auto-underwritten based on property valuation, vacation market occupancy velocity, and MACRS 27.5-yr tax schedule.',
            'last_calculated_at' => now(),
        ];
    }

    /**
     * Upsert and sync metrics for a property.
     */
    public static function syncForProperty(PropertyModel $property, array $attributes = []): PropertyMetrics
    {
        $computed = self::computeDefaults($property, $attributes);

        return PropertyMetrics::updateOrCreate(
            ['property_id' => $property->id],
            $computed
        );
    }

    /**
     * Recalculate metrics for all properties in bulk.
     */
    public static function recalculateAll(?int $specificPropertyId = null): int
    {
        $query = PropertyModel::query();

        if ($specificPropertyId) {
            $query->where('id', $specificPropertyId);
        }

        $count = 0;
        $query->with(['propertyDetails', 'propertyOffering', 'propertyMetrics'])
            ->chunkById(100, function ($properties) use (&$count) {
                foreach ($properties as $property) {
                    self::syncForProperty($property, $property->propertyMetrics ? $property->propertyMetrics->toArray() : []);
                    $count++;
                }
            });

        return $count;
    }
}
