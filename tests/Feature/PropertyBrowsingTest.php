<?php

namespace Tests\Feature;

use Tests\DatabaseTestCase;

class PropertyBrowsingTest extends DatabaseTestCase
{
    public function test_property_listing_survives_hostile_query_strings(): void
    {
        $queries = [
            '?q[]=x',
            '?location[]=x&property_type[]=y',
            '?min_price=abc&max_price=-5&bedrooms=-3&bathrooms=zzz',
            '?min_price=99999999999999999999999',
            '?page=999999',
            '?page=-4',
            '?page[]=1',
            '?sort_by=DROP TABLE',
            '?sort_by[]=price_asc',
            '?q='.urlencode(str_repeat('a', 5000)),
            '?q='.urlencode("'; DROP TABLE properties;--"),
            '?q=%25&location=_',
            '?min_price=500000&max_price=100',
        ];

        foreach ($queries as $query) {
            $this->get('/invest'.$query)->assertOk();
        }
    }

    public function test_property_single_page_handles_bad_slugs_and_ids(): void
    {
        $this->get('/property/'.str_repeat('a', 400))->assertNotFound();
        $this->get('/property/does-not-exist')->assertNotFound();
        $this->get('/property/%3Cscript%3E')->assertNotFound();
        $this->get('/property/999999999')->assertNotFound();
        $this->get('/property_singlepage?id[]=1')->assertStatus(404);
        $this->get('/property_singlepage?id=abc')->assertStatus(404);
        $this->get('/property_singlepage?id=99999999')->assertStatus(404);
    }

    public function test_property_with_a_very_long_name_is_reachable_by_its_slug(): void
    {
        $property = $this->property(['name' => str_repeat('a', 250)]);
        $this->assertGreaterThan(200, strlen($property->slug));

        $this->get('/property/'.$property->slug)->assertOk();
        // a second hit works too
        $this->get('/property/'.$property->slug)->assertOk();
    }

    public function test_listing_filters_still_work_with_valid_input(): void
    {
        $this->get('/invest?q=chalet&bedrooms=2&min_price=100000&max_price=900000&sort_by=price_asc&page=1')
            ->assertOk()
            ->assertViewHas('properties');
    }

    public function test_listing_strips_malformed_filters_before_the_view_sees_them(): void
    {
        $this->get('/invest?q[]=x&bedrooms=abc&sort_by=evil')
            ->assertOk()
            ->assertViewHas('filters', []);
    }
}
