<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateAvailabilityStatus extends Command
{
    protected $signature = 'properties:migrate-availability';
    protected $description = 'Migrate stale availability values to Available / Not Available';

    public function handle(): int
    {
        $updated = DB::table('properties')
            ->whereNotIn('availability', ['Available', 'Not Available'])
            ->update(['availability' => 'Available']);

        $this->info("Updated {$updated} properties to 'Available'.");

        // Show final distribution
        $counts = DB::table('properties')
            ->select('availability', DB::raw('COUNT(*) as cnt'))
            ->groupBy('availability')
            ->get();

        $this->table(['Status', 'Count'], $counts->map(fn($r) => [$r->availability, $r->cnt])->toArray());

        return self::SUCCESS;
    }
}
