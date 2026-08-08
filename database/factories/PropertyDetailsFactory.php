<?php

namespace Database\Factories;

use App\Models\PropertyDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyDetails>
 */
class PropertyDetailsFactory extends Factory
{
    protected $model = PropertyDetails::class;

    public function definition(): array
    {
        return [
            'type' => 'Luxury Fractional Vacation Estate',
            'bedrooms' => fake()->numberBetween(3, 8),
            'baths' => fake()->numberBetween(2, 6),
            'half_baths' => fake()->numberBetween(0, 2),
            'sleeps' => fake()->numberBetween(6, 20),
            'garages' => fake()->numberBetween(1, 4),
            'square_feets' => fake()->numberBetween(2200, 6500),
            'stories' => '2-Story Custom Architectural Frame',
            'units' => 1,
            'lot_size' => fake()->numberBetween(1, 10),
            'year_built' => fake()->numberBetween(2018, 2024),
            'zoning' => 'Commercial / Short-Term Rental Qualified',
            'value' => fake()->numberBetween(450000, 1500000),
        ];
    }
}
