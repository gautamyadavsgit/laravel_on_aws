<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CreateAdminUser::class,
            LookupTablesSeeder::class,
            UserSeeder::class,
            PropertySeeder::class,
            MillionPropertySeeder::class,
        ]);
    }
}
