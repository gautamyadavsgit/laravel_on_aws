<?php

namespace Database\Factories;

use App\Models\PropertyOffering;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PropertyOffering>
 */
class PropertyOfferingFactory extends Factory
{
    protected $model = PropertyOffering::class;

    public function definition(): array
    {
        return [
            'offering_purchase' => fake()->numberBetween(450000, 950000),
            'offering_build_cost' => fake()->numberBetween(30000, 60000),
            'offering_improvements' => fake()->numberBetween(15000, 35000),
            'offering_closing_cost' => (string) fake()->numberBetween(8000, 16000),
            'offering_sourcing_fees' => (string) fake()->numberBetween(10000, 20000),
        ];
    }
}
