<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class MillionPropertySeeder extends Seeder
{
    /**
     * Run the high-volume bulk seeder for 1,000,000 properties.
     */
    public function run(): void
    {
        $output = $this->command ? $this->command->getOutput() : null;

        Artisan::call('property:seed-million', [
            '--count' => 1000000,
            '--chunk' => 5000,
        ], $output);
    }
}
