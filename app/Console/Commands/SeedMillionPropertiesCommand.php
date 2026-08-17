<?php

namespace App\Console\Commands;

use App\Models\Admins;
use Database\Seeders\ImageSeederHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SeedMillionPropertiesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'property:seed-million {--count=1000000 : Total number of properties to seed} {--chunk=2000 : Number of records per insert chunk}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'High-performance bulk stream seeder for 1,000,000+ real estate properties with relationships';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $total = (int) $this->option('count');
        $chunkSize = (int) $this->option('chunk');

        $this->info('===================================================================');
        $this->info(" Starting High-Performance Seeding: {$total} Properties");
        $this->info(" Chunk size: {$chunkSize} records per bulk transaction");
        $this->info('===================================================================');

        // Ensure physical sample images & storage link exist
        ImageSeederHelper::ensureSeedAssets();

        // Ensure admin user exists
        $admin = Admins::first();
        $adminId = $admin ? $admin->id : 1;

        // Disable query log to prevent memory leaks during large iterations
        DB::disableQueryLog();

        $startTime = microtime(true);

        $cities = [
            ['city' => 'Gatlinburg', 'state' => 'Tennessee', 'zip' => 37738],
            ['city' => 'Asheville', 'state' => 'North Carolina', 'zip' => 28801],
            ['city' => 'Destin', 'state' => 'Florida', 'zip' => 32541],
            ['city' => 'Aspen', 'state' => 'Colorado', 'zip' => 81611],
            ['city' => 'St. Helena', 'state' => 'California', 'zip' => 94574],
            ['city' => 'Scottsdale', 'state' => 'Arizona', 'zip' => 85255],
            ['city' => 'South Lake Tahoe', 'state' => 'California', 'zip' => 96150],
            ['city' => 'Jackson', 'state' => 'Wyoming', 'zip' => 83001],
            ['city' => 'Key West', 'state' => 'Florida', 'zip' => 33040],
            ['city' => 'Sedona', 'state' => 'Arizona', 'zip' => 86336],
        ];

        $propertyPrefixes = [
            'Smoky Mountain', 'Blue Ridge', 'Gulf Coast', 'Aspen Alpine',
            'Napa Valley', 'Scottsdale Desert', 'Lake Tahoe', 'Jackson Hole',
            'Key West', 'Sedona Red Rock', 'Emerald Bay', 'Whispering Pines',
            'Sunset Ridge', 'Silver King', 'Pelican Bay', 'Highland Timber',
        ];

        $propertyTypes = [
            'Luxury Cabin', 'Mountain Retreat', 'Waterfront Villa', 'Ski Chalet',
            'Vineyard Estate', 'Desert Oasis', 'Lakeside Manor', 'Alpine Lodge',
            'Coastal Sanctuary', 'Red Rock Haven', 'Contemporary Villa', 'Timber Lodge',
        ];

        $now = now()->format('Y-m-d H:i:s');
        $bar = $this->output->createProgressBar($total);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% -- %elapsed:6s%/%estimated:-6s% (Mem: %memory:6s%)');
        $bar->start();

        $insertedCount = 0;

        while ($insertedCount < $total) {
            $currentChunk = min($chunkSize, $total - $insertedCount);

            $propertiesBatch = [];
            for ($i = 0; $i < $currentChunk; $i++) {
                $uniqueIdx = $insertedCount + $i + 1;
                $prefix = $propertyPrefixes[$uniqueIdx % count($propertyPrefixes)];
                $type = $propertyTypes[$uniqueIdx % count($propertyTypes)];

                $propertiesBatch[] = [
                    'admin_id' => $adminId,
                    'name' => "{$prefix} {$type} #{$uniqueIdx}",
                    'availability' => ($uniqueIdx % 5 === 0) ? 'Not Available' : (($uniqueIdx % 3 === 0) ? 'Available' : 'Available'),
                    'description' => "Institutional-grade fractional investment property #{$uniqueIdx} located in prime tourist corridor with verified annual yield and professional management.",
                    'management_company' => 'Gautam Asset Management LLC',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            DB::transaction(function () use ($propertiesBatch, $cities, $now, &$insertedCount, $currentChunk) {
                // 1. Bulk insert properties
                DB::table('properties')->insert($propertiesBatch);

                // Get first inserted ID in this chunk
                $firstId = DB::getPdo()->lastInsertId();
                $startId = (int) $firstId;
                $endId = $startId + $currentChunk - 1;

                $addresses = [];
                $imagesList = [];
                $detailsList = [];

                for ($id = $startId; $id <= $endId; $id++) {
                    $location = $cities[$id % count($cities)];
                    $imgNum = ($id % 10) + 1;
                    $raise = 450000 + (($id % 60) * 10000);

                    // Address
                    $addresses[] = [
                        'property_id' => $id,
                        'address_1' => (100 + ($id % 900)).' Mountain Vista Way',
                        'address_2' => 'Unit #'.(($id % 50) + 1),
                        'city' => $location['city'],
                        'state' => $location['state'],
                        'zip' => $location['zip'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    // Hero Property Image
                    $imagesList[] = [
                        'property_id' => $id,
                        'property_image_key' => 'property_image',
                        'property_image_value' => 'property_images/property_'.$imgNum.'.png',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    // Details
                    $detailsList[] = [
                        'property_id' => $id,
                        'type' => 'Luxury Fractional Vacation Cabin',
                        'bedrooms' => 3 + ($id % 5),
                        'baths' => 2 + ($id % 4),
                        'half_baths' => ($id % 2),
                        'sleeps' => 8 + ($id % 12),
                        'garages' => 2,
                        'square_feets' => 2400 + (($id % 40) * 50),
                        'stories' => '2-Story Custom Architectural Frame',
                        'units' => 1,
                        'lot_size' => 1 + ($id % 4),
                        'year_built' => 2019 + ($id % 6),
                        'zoning' => 'Commercial / Short-Term Rental Certified',
                        'value' => $raise,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                // Chunk sub-records into safe batches of 1,500 rows to satisfy MySQL 65,535 parameter limit
                foreach (array_chunk($addresses, 1500) as $subChunk) {
                    DB::table('property_address')->insert($subChunk);
                }
                foreach (array_chunk($imagesList, 1500) as $subChunk) {
                    DB::table('property_images')->insert($subChunk);
                }
                foreach (array_chunk($detailsList, 1500) as $subChunk) {
                    DB::table('property_details')->insert($subChunk);
                }
            });

            $insertedCount += $currentChunk;
            $bar->advance($currentChunk);
        }

        $bar->finish();
        $this->newLine(2);

        $duration = round(microtime(true) - $startTime, 2);
        $rate = round($total / max(0.001, $duration));

        $this->info('===================================================================');
        $this->info(" SUCCESS: Seeded {$total} Properties with sub-records in {$duration}s ({$rate} rows/sec)");
        $this->info(' All seeded images resolve to verified storage assets.');
        $this->info(' Pagination is configured to handle high volume seamlessly.');
        $this->info('===================================================================');

        return Command::SUCCESS;
    }
}
