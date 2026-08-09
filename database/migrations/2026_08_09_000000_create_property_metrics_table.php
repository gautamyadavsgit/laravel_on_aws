<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('property_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->unique()->constrained('properties')->cascadeOnDelete();
            
            // Cash Flow & Operational Yields
            $table->decimal('gross_annual_rent', 12, 2)->default(0)->comment('Gross projected yearly rental income');
            $table->decimal('operating_expenses', 12, 2)->default(0)->comment('Annual operational, maintenance & management fees');
            $table->decimal('net_operating_income', 12, 2)->default(0)->comment('Net Operating Income (NOI)');
            $table->decimal('cap_rate', 5, 2)->default(0)->comment('Capitalization Rate (%)');
            $table->decimal('annual_cash_flow', 12, 2)->default(0)->comment('Net yearly cash flow distributed to investors');
            $table->decimal('cash_on_cash_return', 5, 2)->default(0)->comment('Cash-on-Cash Return (%)');
            $table->decimal('projected_irr', 5, 2)->default(0)->comment('Projected 5-year Internal Rate of Return (%)');
            
            // Capital Growth & Appreciation Forecasts
            $table->decimal('estimated_appreciation_rate', 5, 2)->default(4.50)->comment('Estimated annual property appreciation (%)');
            $table->decimal('projected_value_5yr', 14, 2)->default(0)->comment('5-Year projected asset valuation');
            $table->decimal('projected_value_10yr', 14, 2)->default(0)->comment('10-Year projected asset valuation');
            
            // Tax Efficiency & Advantages
            $table->decimal('annual_depreciation_deduction', 12, 2)->default(0)->comment('Estimated annual tax-deductible depreciation');
            $table->decimal('tax_savings_estimate', 12, 2)->default(0)->comment('Estimated tax savings at standard rate');
            $table->boolean('is_1031_exchange_eligible')->default(true)->comment('Whether property is 1031 exchange qualified');
            $table->boolean('cost_segregation_eligible')->default(true)->comment('Whether accelerated cost segregation applies');
            
            // Risk, Occupancy & Portfolio Diversification
            $table->decimal('diversification_score', 4, 1)->default(8.5)->comment('Market diversification rating out of 10');
            $table->decimal('occupancy_rate_projected', 5, 2)->default(85.00)->comment('Historical / projected occupancy %');
            
            // Investment Goal Suitability Ratings (0-100 Score or Tier)
            $table->unsignedTinyInteger('cash_flow_rating')->default(90)->comment('Suitability score for cash-flow investors (0-100)');
            $table->unsignedTinyInteger('appreciation_rating')->default(85)->comment('Suitability score for appreciation investors (0-100)');
            $table->unsignedTinyInteger('tax_benefit_rating')->default(88)->comment('Suitability score for tax-efficiency investors (0-100)');
            $table->unsignedTinyInteger('diversification_rating')->default(92)->comment('Suitability score for portfolio diversification (0-100)');
            
            $table->text('calculation_notes')->nullable()->comment('Underwriting assumptions and market notes');
            $table->timestamp('last_calculated_at')->nullable()->comment('Timestamp of last recalculation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_metrics');
    }
};
