<?php

namespace Database\Factories;

use App\Models\PropertyAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyAddress>
 */
class PropertyAddressFactory extends Factory
{
    protected $model = PropertyAddress::class;

    public function definition(): array
    {
        return [
            'address_1' => fake()->streetAddress(),
            'address_2' => 'Suite ' . fake()->numberBetween(100, 500),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'zip' => fake()->numberBetween(10000, 99999),
        ];
    }
}
