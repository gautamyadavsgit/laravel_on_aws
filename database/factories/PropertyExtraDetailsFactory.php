<?php

namespace Database\Factories;

use App\Models\PropertyExtraDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyExtraDetails>
 */
class PropertyExtraDetailsFactory extends Factory
{
    protected $model = PropertyExtraDetails::class;

    public function definition(): array
    {
        return [
            'deed_fraction_1' => '1/12340 Fractional Equity LLC Unit',
            'deed_fraction_2' => 'Series 2024-REI-Deed',
            'leveraged' => '0',
            'leverage_amount' => 0,
            'leverage_percent' => 0,
            'rent_rate' => fake()->numberBetween(55000, 120000),
            'market_rent_rate' => fake()->numberBetween(60000, 130000),
            'occupancy_rate' => fake()->numberBetween(82, 94),
            'occupancy_status' => 'Occupied',
        ];
    }
}
