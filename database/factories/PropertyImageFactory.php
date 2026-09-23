<?php

namespace Database\Factories;

use App\Models\PropertyImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PropertyImage>
 */
class PropertyImageFactory extends Factory
{
    protected $model = PropertyImage::class;

    public function definition(): array
    {
        $imageNum = fake()->numberBetween(1, 10);

        return [
            'property_image_key' => 'property_image',
            'property_image_value' => 'property_images/property_'.$imageNum.'.png',
        ];
    }
}
