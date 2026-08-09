<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyMetrics extends Model
{
    use HasFactory;

    protected $table = 'property_metrics';

    protected $fillable = [
        'property_id',
        'gross_annual_rent',
        'operating_expenses',
        'net_operating_income',
        'cap_rate',
        'annual_cash_flow',
        'cash_on_cash_return',
        'projected_irr',
        'estimated_appreciation_rate',
        'projected_value_5yr',
        'projected_value_10yr',
        'annual_depreciation_deduction',
        'tax_savings_estimate',
        'is_1031_exchange_eligible',
        'cost_segregation_eligible',
        'diversification_score',
        'occupancy_rate_projected',
        'cash_flow_rating',
        'appreciation_rating',
        'tax_benefit_rating',
        'diversification_rating',
        'calculation_notes',
        'last_calculated_at',
    ];

    protected $casts = [
        'gross_annual_rent' => 'decimal:2',
        'operating_expenses' => 'decimal:2',
        'net_operating_income' => 'decimal:2',
        'cap_rate' => 'decimal:2',
        'annual_cash_flow' => 'decimal:2',
        'cash_on_cash_return' => 'decimal:2',
        'projected_irr' => 'decimal:2',
        'estimated_appreciation_rate' => 'decimal:2',
        'projected_value_5yr' => 'decimal:2',
        'projected_value_10yr' => 'decimal:2',
        'annual_depreciation_deduction' => 'decimal:2',
        'tax_savings_estimate' => 'decimal:2',
        'is_1031_exchange_eligible' => 'boolean',
        'cost_segregation_eligible' => 'boolean',
        'diversification_score' => 'decimal:1',
        'occupancy_rate_projected' => 'decimal:2',
        'cash_flow_rating' => 'integer',
        'appreciation_rating' => 'integer',
        'tax_benefit_rating' => 'integer',
        'diversification_rating' => 'integer',
        'last_calculated_at' => 'datetime',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(PropertyModel::class, 'property_id');
    }

    public function propertyModel(): BelongsTo
    {
        return $this->belongsTo(PropertyModel::class, 'property_id');
    }

    /**
     * Get primary highlight metrics based on investor goal ID or slug
     */
    public function getGoalHighlight(int|string|null $goal): array
    {
        // 1: Cash Flow, 2: Capital Appreciation / Generational Wealth, 3: Tax Offsets, 4: Diversification / Inflation
        return match ((string) $goal) {
            '1', 'cash_flow', 'Generate Steady Quarterly Cash Flow' => [
                'title' => 'Quarterly Cash Flow Alignment',
                'primary_label' => 'Cap Rate Yield',
                'primary_value' => ($this->cap_rate ?? 0) . '%',
                'secondary_label' => 'Net Annual Cash Flow',
                'secondary_value' => '$' . number_format((float) ($this->annual_cash_flow ?? 0)),
                'badge' => 'High Dividend Yield',
                'score' => $this->cash_flow_rating ?? 92,
            ],
            '2', 'appreciation', 'Build Generational Real Estate Wealth' => [
                'title' => 'Capital Appreciation Alignment',
                'primary_label' => '5-Yr Projected Value',
                'primary_value' => '$' . number_format((float) ($this->projected_value_5yr ?? 0)),
                'secondary_label' => 'Annual Growth Rate',
                'secondary_value' => '+' . ($this->estimated_appreciation_rate ?? 0) . '%/yr',
                'badge' => 'High Growth Corridor',
                'score' => $this->appreciation_rating ?? 88,
            ],
            '3', 'tax_benefits', 'Maximize Tax Depreciation Offsets' => [
                'title' => 'Tax Efficiency Alignment',
                'primary_label' => 'Annual Depreciation',
                'primary_value' => '$' . number_format((float) ($this->annual_depreciation_deduction ?? 0)),
                'secondary_label' => '1031 Exchange Ready',
                'secondary_value' => $this->is_1031_exchange_eligible ? 'Eligible' : 'Standard',
                'badge' => 'Tax Shelter Optimized',
                'score' => $this->tax_benefit_rating ?? 94,
            ],
            default => [
                'title' => 'Portfolio Diversification Alignment',
                'primary_label' => 'Diversification Score',
                'primary_value' => ($this->diversification_score ?? 8.5) . ' / 10',
                'secondary_label' => 'Projected Occupancy',
                'secondary_value' => ($this->occupancy_rate_projected ?? 85) . '%',
                'badge' => 'Prime Vacation Asset',
                'score' => $this->diversification_rating ?? 90,
            ],
        };
    }
}
