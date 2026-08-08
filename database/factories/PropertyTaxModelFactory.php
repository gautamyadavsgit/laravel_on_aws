<?php

namespace Database\Factories;

use App\Models\PropertyTaxModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyTaxModel>
 */
class PropertyTaxModelFactory extends Factory
{
    protected $model = PropertyTaxModel::class;

    public function definition(): array
    {
        $taxIndex = fake()->numberBetween(1, 3);

        return [
            'tax_key' => 'tax_' . $taxIndex,
            'tax_value' => (string) fake()->numberBetween(2500, 5800),
        ];
    }
}
