<?php

namespace Database\Factories;

use App\Models\MarketDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MarketDetails>
 */
class MarketDetailsFactory extends Factory
{
    protected $model = MarketDetails::class;

    public function definition(): array
    {
        return [
            'market_title' => 'High-Growth Mountain & Coastal Vacation Corridors',
            'market_image' => 'market_image/market_1.png',
            'market_description' => 'Target market demonstrates strong historical occupancy rates (>85%), increasing ADR (Average Daily Rate), and expanding tourist infrastructure.',
        ];
    }
}
