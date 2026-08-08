<?php

namespace Database\Factories;

use App\Models\PropertyFinancialDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyFinancialDetail>
 */
class PropertyFinancialDetailFactory extends Factory
{
    protected $model = PropertyFinancialDetail::class;

    public function definition(): array
    {
        return [
            'management_fee' => fake()->numberBetween(8, 12),
            'cash_reserve' => fake()->numberBetween(15000, 30000),
            'hold_period' => fake()->numberBetween(3, 7),
            'annual_appreciation' => (string) fake()->randomFloat(2, 4.5, 7.5),
            'aum_fee_1' => (string) fake()->numberBetween(1000, 2000),
            'aum_fee_2' => (string) fake()->numberBetween(2000, 3000),
            'aum_fee_3' => (string) fake()->numberBetween(3000, 4000),
            'average_time_to_rent' => (string) fake()->numberBetween(7, 21),
        ];
    }
}
