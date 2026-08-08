<?php

namespace Database\Factories;

use App\Models\PropertyAacf;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyAacf>
 */
class PropertyAacfFactory extends Factory
{
    protected $model = PropertyAacf::class;

    public function definition(): array
    {
        $rent = fake()->numberBetween(60000, 110000);
        $expenses = fake()->numberBetween(12000, 24000);
        $net = $rent - $expenses;

        return [
            'annual_rent_amount' => $rent,
            'annual_rent_gross_yield' => fake()->randomFloat(2, 8.5, 12.8),
            'aacf_expences' => $expenses,
            'aacf_net' => $net,
        ];
    }
}
