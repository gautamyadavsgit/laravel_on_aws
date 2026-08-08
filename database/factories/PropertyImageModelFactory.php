<?php

namespace Database\Factories;

use App\Models\PropertyImageModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyImageModel>
 */
class PropertyImageModelFactory extends Factory
{
    protected $model = PropertyImageModel::class;

    public function definition(): array
    {
        $imageNum = fake()->numberBetween(1, 10);

        return [
            'property_image_key' => 'property_image',
            'property_image_value' => 'property_images/property_' . $imageNum . '.png',
        ];
    }
}
