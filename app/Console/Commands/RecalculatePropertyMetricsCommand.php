<?php

namespace App\Console\Commands;

use App\Models\PropertyModel;
use App\Services\PropertyMetricsService;
use Illuminate\Console\Command;

class RecalculatePropertyMetricsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'property:recalculate-metrics {--property= : Optional specific property ID to recalculate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate cash-flow, cap rates, appreciation projections, and tax metrics for fractional real estate properties';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $propertyId = $this->option('property') ? (int) $this->option('property') : null;

        $this->info('==========================================================');
        $this->info(' Starting Property Underwriting & Metrics Recalculation');
        if ($propertyId) {
            $this->info(" Targeted Property ID: #{$propertyId}");
        } else {
            $this->info(' Scope: All active properties in the database');
        }
        $this->info('==========================================================');

        $startTime = microtime(true);

        if ($propertyId) {
            $property = PropertyModel::find($propertyId);
            if (! $property) {
                $this->error("Property with ID #{$propertyId} not found.");

                return Command::FAILURE;
            }

            PropertyMetricsService::syncForProperty($property);
            $processedCount = 1;
        } else {
            $processedCount = PropertyMetricsService::recalculateAll();
        }

        $duration = round(microtime(true) - $startTime, 2);

        $this->info(" Successfully updated metrics for {$processedCount} property(ies) in {$duration}s.");
        $this->info(' Formulas updated: NOI, Cap Rate, 5-Yr / 10-Yr Compound Growth, MACRS 27.5-Yr Tax Deductions, Goal Suitability.');

        return Command::SUCCESS;
    }
}
