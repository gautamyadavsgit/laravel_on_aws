<?php

namespace Database\Factories;

use App\Models\PropertyFloorplan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PropertyFloorplan>
 */
class PropertyFloorplanFactory extends Factory
{
    protected $model = PropertyFloorplan::class;

    public function definition(): array
    {
        $planNum = fake()->numberBetween(1, 6);

        return [
            'key' => 'Floorplan Architectural Level '.$planNum,
            'value' => 'floorplan_images/floorplan_'.$planNum.'.png',
        ];
    }
}
