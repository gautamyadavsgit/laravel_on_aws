<?php

namespace Database\Factories;

use App\Models\PropertyUrl;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyUrl>
 */
class PropertyUrlFactory extends Factory
{
    protected $model = PropertyUrl::class;

    public function definition(): array
    {
        return [
            'google_map' => 'https://maps.google.com/?q=' . urlencode(fake()->city()),
            'zillow' => 'https://www.zillow.com/homedetails/' . fake()->slug(),
            'airbnb' => 'https://www.airbnb.com/rooms/' . fake()->numberBetween(10000000, 99999999),
            'vrbo' => 'https://www.vrbo.com/' . fake()->numberBetween(1000000, 9999999),
            'alt_listing_1' => 'https://booking.com/hotel/us/' . fake()->slug(),
            'alt_listing_2' => 'https://expedia.com/vacation-rental/' . fake()->slug(),
            'alt_listing_3' => 'https://tripadvisor.com/VacationRentals-' . fake()->slug(),
        ];
    }
}
