<?php

namespace Database\Factories;

use App\Enums\PropertyAvailability;
use App\Models\Admin;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition(): array
    {
        return [
            'admin_id' => fn () => Admin::query()->value('id') ?? Admin::factory()->create()->id,
            'name' => fake()->unique()->words(3, true).' Estate',
            'availability' => PropertyAvailability::Available,
            'description' => fake()->paragraph(),
            'management_company' => fake()->company(),

            'address_1' => fake()->streetAddress(),
            'address_2' => 'Suite '.fake()->numberBetween(100, 500),
            'city' => fake()->city(),
            'state' => fake()->randomElement(['TN', 'NC', 'GA', 'FL', 'TX', 'CO']),
            'zip' => (string) fake()->numberBetween(10000, 99999),

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
