<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\PropertyModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PropertySlugTest extends TestCase
{
    use DatabaseTransactions;

    public function test_property_single_page_resolves_by_slug(): void
    {
        $property = PropertyModel::first();
        if (! $property) {
            $property = PropertyModel::create([
                'name' => 'Blue Ridge Luxury Chalet',
                'description' => 'A premier luxury cabin nestled in the mountains.',
                'management_company' => 'Blue Ridge Asset Management',
                'admin_id' => 1,
                'availability' => 'Available',
            ]);
        }

        $this->assertNotEmpty($property->slug);

        $response = $this->get('/property/'.$property->slug);
        $response->assertStatus(200);
        $response->assertViewHas('featuredProperty');
    }

    public function test_legacy_property_single_page_redirects_to_canonical_slug(): void
    {
        $property = PropertyModel::first();
        if ($property) {
            $response = $this->get('/property_singlepage?id='.$property->id);
            $response->assertRedirect('/property/'.$property->slug);
        }
    }

    public function test_each_slug_renders_its_own_property_and_only_real_images(): void
    {
        $admin = Admin::first() ?? Admin::create(['username' => 'slug-test', 'email' => 'slug-test@example.com', 'password' => Hash::make('password123')]);

        $withImage = PropertyModel::create(['name' => 'Slug Test Alpha', 'description' => 'Test description.', 'management_company' => 'Test Co', 'admin_id' => $admin->id, 'availability' => 'Available']);
        $withImage->propertyImage()->create(['property_image_key' => 'property_image', 'property_image_value' => 'property_images/alpha.png']);
        $withoutImage = PropertyModel::create(['name' => 'Slug Test Beta', 'description' => 'Test description.', 'management_company' => 'Test Co', 'admin_id' => $admin->id, 'availability' => 'Available']);

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
