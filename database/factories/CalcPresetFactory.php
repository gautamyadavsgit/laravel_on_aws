<?php

namespace Database\Factories;

use App\Models\CalcPreset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CalcPreset>
 */
class CalcPresetFactory extends Factory
{
    protected $model = CalcPreset::class;

    public function definition(): array
    {
        $presetIndex = fake()->numberBetween(1, 6);

        return [
            'key' => 'calc_preset_' . $presetIndex,
            'value' => (string) ($presetIndex * 10),
        ];
    }
}
