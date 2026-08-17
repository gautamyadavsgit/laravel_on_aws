<?php

namespace Tests\Feature;

use App\Models\PropertyModel;
use Tests\TestCase;

class PropertySlugTest extends TestCase
{
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
}
