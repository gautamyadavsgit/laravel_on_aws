<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\PropertyModel;
use App\Models\User;
use App\Models\UserSearchLog;
use App\Services\PropertyService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class PropertyServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected string $tag;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tag = 'PSvc'.Str::random(8);
    }

    protected function makeProperty(string $suffix, array $details = [], array $address = []): PropertyModel
    {
        $property = PropertyModel::create([
            'name' => "{$this->tag} {$suffix}",
            'description' => 'Service test property.',
            'management_company' => 'Service Test Co',
            'admin_id' => (Admin::first() ?? Admin::factory()->create())->id,
            'availability' => 'Available',
        ]);

        $property->details()->create(array_merge([
            'type' => 'Cabin',
            'bedrooms' => 3,
            'baths' => 2,
            'half_baths' => 0,
            'sleeps' => 6,
            'garages' => 1,
            'square_feets' => 2000,
            'stories' => '2',
            'units' => 1,
            'lot_size' => 1,
            'year_built' => 2020,
            'zoning' => 'R-1',
            'value' => 500000,
        ], $details));

        $property->address()->create(array_merge([
            'address_1' => '1 Test Road',
            'address_2' => 'Suite 1',
            'city' => 'Testville',
            'state' => 'TN',
            'zip' => '37738',
        ], $address));

        return $property;
    }

    /** @return list<string> property names (without the test tag) in result order */
    protected function names(array $filters): array
    {
        $result = app(PropertyService::class)->getFilteredProperties($filters + ['q' => $this->tag], 50);

        return collect($result->items())
            ->map(fn (PropertyModel $property) => trim(Str::after($property->name, $this->tag)))
            ->all();
    }

    public function test_price_range_filters_by_property_value(): void
    {
        $this->makeProperty('cheap', ['value' => 200000]);
        $this->makeProperty('mid', ['value' => 500000]);
        $this->makeProperty('pricey', ['value' => 900000]);

        $this->assertEqualsCanonicalizing(['mid'], $this->names(['min_price' => 300000, 'max_price' => 600000]));
        $this->assertEqualsCanonicalizing(['mid', 'pricey'], $this->names(['min_price' => 300000]));
        $this->assertEqualsCanonicalizing(['cheap', 'mid'], $this->names(['max_price' => 500000]));
    }

    public function test_bedroom_bathroom_and_type_filters(): void
    {
        $this->makeProperty('small', ['bedrooms' => 1, 'baths' => 1, 'type' => 'Condo']);
        $this->makeProperty('large', ['bedrooms' => 5, 'baths' => 4, 'type' => 'Cabin']);

        $this->assertSame(['large'], $this->names(['bedrooms' => 3]));
        $this->assertSame(['large'], $this->names(['bathrooms' => 3]));
        $this->assertSame(['small'], $this->names(['property_type' => 'Condo']));
    }

    public function test_location_filter_matches_city_state_or_zip(): void
    {
        $this->makeProperty('tn', [], ['city' => 'Gatlinburg', 'state' => 'TN']);
        $this->makeProperty('fl', [], ['city' => 'Miami', 'state' => 'FL', 'zip' => '33101']);

        $this->assertSame(['tn'], $this->names(['location' => 'Gatlinburg']));
        $this->assertSame(['fl'], $this->names(['location' => 'FL']));
        $this->assertSame(['fl'], $this->names(['location' => '33101']));
    }

    public function test_sorting_by_price_and_age(): void
    {
        $this->makeProperty('a', ['value' => 300000]);
        $this->makeProperty('b', ['value' => 100000]);
        $this->makeProperty('c', ['value' => 200000]);

        $this->assertSame(['b', 'c', 'a'], $this->names(['sort_by' => 'price_asc']));
        $this->assertSame(['a', 'c', 'b'], $this->names(['sort_by' => 'price_desc']));
    }

    public function test_keyword_search_treats_input_as_a_literal_string(): void
    {
        $this->makeProperty('plain');

        $this->assertSame([], $this->names(['q' => "{$this->tag}' OR 1=1 --"]));
    }

    public function test_recommendations_prefer_searched_locations_and_fill_up_with_other_properties(): void
    {
        $user = User::factory()->create();
        $match = $this->makeProperty('match', [], ['city' => 'Sevierville', 'state' => 'TN']);
        $this->makeProperty('other', [], ['city' => 'Austin', 'state' => 'TX']);

        UserSearchLog::create([
            'user_id' => $user->id,
            'session_id' => 'test-session',
            'location' => 'Sevierville',
            'results_count' => 1,
        ]);

        $recommendations = app(PropertyService::class)->getPersonalizedRecommendations($user, 3);

        $this->assertLessThanOrEqual(3, $recommendations->count());
        $this->assertTrue($recommendations->contains('id', $match->id));
        $this->assertSame($recommendations->count(), $recommendations->unique('id')->count());
    }

    public function test_property_detail_cache_is_cleared_on_demand(): void
    {
        $property = $this->makeProperty('cached');
        $service = app(PropertyService::class);

        $this->assertSame($property->id, $service->getPropertyBySlug($property->slug)->id);

        $property->update(['description' => 'Changed description']);
        $this->assertSame('Service test property.', $service->getPropertyBySlug($property->slug)->description);

        $service->clearPropertiesCache($property->slug);
        $this->assertSame('Changed description', $service->getPropertyBySlug($property->slug)->description);
    }
}
