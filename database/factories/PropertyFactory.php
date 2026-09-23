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

    public function unavailable(): static
    {
        return $this->state(['availability' => PropertyAvailability::NotAvailable]);
    }

    public function withAddress(array $attributes = []): static
    {
        return $this->has(PropertyAddressFactory::new($attributes), 'address');
    }

    public function withDetails(array $attributes = []): static
    {
        return $this->has(PropertyDetailFactory::new($attributes), 'details');
    }

    public function withOffering(array $attributes = []): static
    {
        return $this->has(PropertyOfferingFactory::new($attributes), 'offering');
    }

    public function withImages(int $count = 3): static
    {
        return $this->has(PropertyImageFactory::new()->count($count), 'images');
    }

    public function withDocuments(int $count = 3): static
    {
        return $this->has(PropertyDocumentFactory::new()->count($count), 'documents');
    }

    public function complete(): static
    {
        return $this->withAddress()->withDetails()->withOffering()->withImages()->withDocuments();
    }
}
