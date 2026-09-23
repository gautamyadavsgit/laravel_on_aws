<?php

namespace Database\Factories;

use App\Enums\PropertyAvailability;
use App\Models\Admin;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition(): array
    {
        return [
            'admin_id' => fn () => Admin::query()->value('id') ?? Admin::factory()->create()->id,
            'name' => fake()->unique()->words(3, true).' Estate',
            'availability' => PropertyAvailability::Available,
            'description' => fake()->paragraph(),
            'management_company' => fake()->company(),
        ];
    }

    public function withAddress(array $attributes = []): static
    {
        return $this->has(PropertyAddressFactory::new($attributes), 'address');
    }

    public function withDetails(array $attributes = []): static
    {
        return $this->has(PropertyDetailFactory::new($attributes), 'details');
    }
}
