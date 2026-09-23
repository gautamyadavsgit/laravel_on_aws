<?php

namespace Tests\Feature;

use App\Models\Property;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PropertySlugTest extends TestCase
{
    use DatabaseTransactions;

    protected function makeProperty(string $name): Property
    {
        return Property::factory()->create(['name' => $name]);
    }

    public function test_property_single_page_resolves_by_slug(): void
    {
        $property = $this->makeProperty('Blue Ridge Luxury Chalet');

        $this->assertNotEmpty($property->slug);

        $this->get('/property/'.$property->slug)
            ->assertOk()
            ->assertViewHas('featuredProperty');
    }

    public function test_legacy_property_single_page_redirects_to_canonical_slug(): void
    {
        $property = $this->makeProperty('Legacy Redirect Estate');

        $this->get('/property_singlepage?id='.$property->id)
            ->assertRedirect('/property/'.$property->slug);
    }

    public function test_slug_of_a_deleted_property_is_not_reused(): void
    {
        $first = $this->makeProperty('Reused Name Estate');
        $slug = $first->slug;
        $first->delete();

        $second = $this->makeProperty('Reused Name Estate');

        $this->assertNotSame($slug, $second->slug);
    }

    public function test_each_slug_renders_its_own_property_and_only_real_images(): void
    {
        $withImage = $this->makeProperty('Slug Test Alpha');
        $withImage->images()->create(['property_image_key' => 'property_image', 'property_image_value' => 'property_images/alpha.png']);
        $withoutImage = $this->makeProperty('Slug Test Beta');

        $this->get('/property/'.$withImage->slug)
            ->assertOk()
            ->assertSee('Slug Test Alpha')
            ->assertDontSee('Slug Test Beta')
            ->assertSee('property_images/alpha.png', false);

        $this->get('/property/'.$withoutImage->slug)
            ->assertOk()
            ->assertSee('Slug Test Beta')
            ->assertDontSee('property_images/property_1.png', false)
            ->assertDontSee('data-carousel-prev', false);
    }
}
