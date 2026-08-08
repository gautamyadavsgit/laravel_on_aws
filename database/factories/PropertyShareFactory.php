<?php

namespace Database\Factories;

use App\Models\PropertyShare;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyShare>
 */
class PropertyShareFactory extends Factory
{
    protected $model = PropertyShare::class;

    public function definition(): array
    {
        $raise = fake()->numberBetween(500000, 1000000);
        $price = 50;
        $totalRaiseShares = (int) ($raise / $price);
        $devShares = (int) ($totalRaiseShares * 0.08);

        return [
            'equity_raise' => $raise,
            'price_per_share_deed' => $price,
            'total_developer_share_deeds' => $devShares,
            'total_raise_share_deeds' => $totalRaiseShares,
            'total_share_deeds' => $totalRaiseShares + $devShares,
            'first_dividend_date' => now()->addMonths(3)->format('Y-m-d'),
            'seccond_dividend_date' => now()->addMonths(6)->format('Y-m-d'),
        ];
    }
}
