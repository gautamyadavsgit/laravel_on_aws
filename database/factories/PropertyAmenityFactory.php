<?php

namespace Database\Factories;

use App\Models\PropertyAmenity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PropertyAmenity>
 */
class PropertyAmenityFactory extends Factory
{
    protected $model = PropertyAmenity::class;

    public function definition(): array
    {
        $amenityLists = [
            'Panoramic Mountain View, 8-Person Hot Tub, Starlink High Speed Internet, Stone Fireplace, Game Room & Billiards, Keyless Smart Lock',
            'Waterfront Private Dock, Heated Infinity Pool, Outdoor Kitchen & BBQ, Custom Wine Cellar, Tesla EV Charger, Home Cinema Suite',
            'Ski-in Ski-out Access, Sauna & Spa, Heated Ski Locker, Radiant Floor Heating, Gourmet Viking Kitchen, Mountain View Fire Pit',
            'Private Beach Access, Oceanfront Balcony, Concierge Guest Support, Kayaks & Paddleboards, High-End Audio System, Security Surveillance',
        ];

        return [
            'property_amenities' => fake()->randomElement($amenityLists),
        ];
    }
}
