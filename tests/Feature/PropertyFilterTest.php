<?php

namespace Tests\Feature;

use App\Models\Property;
use Illuminate\Support\Str;
use Tests\DatabaseTestCase;

class PropertyFilterTest extends DatabaseTestCase
{
    protected string $tag;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tag = 'PSvc'.Str::random(8);
    }

    protected function makeProperty(string $suffix, array $details = [], array $address = []): Property
    {
        return Property::factory()->create(array_merge(
            ['type' => 'Cabin', 'bedrooms' => 3, 'baths' => 2, 'value' => 500000],
            ['city' => 'Testville', 'state' => 'TN', 'zip' => '37738'],
            $details,
            $address,
            ['name' => "{$this->tag} {$suffix}"]
        ));
    }

    /** @return list<string> property names (without the test tag) in result order */
    protected function names(array $filters): array
    {
        $result = Property::filter($filters + ['q' => $this->tag])->sortedBy($filters['sort_by'] ?? null)->paginate(50);

        return collect($result->items())
            ->map(fn (Property $property) => trim(Str::after($property->name, $this->tag)))
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

    public function test_percent_and_underscore_in_search_are_literal(): void
    {
        $this->makeProperty('plain');

        $this->assertSame([], $this->names(['location' => '%']));
        $this->assertSame([], $this->names(['location' => '_estvill_']));
        $this->assertSame(['plain'], $this->names(['location' => 'estvill']));
    }

    public function test_price_sort_pages_do_not_repeat_or_skip_tied_properties(): void
    {
        foreach (range(1, 6) as $n) {
            $this->makeProperty("tie{$n}", ['value' => 400000]);
        }

        $seen = [];
        foreach ([1, 2, 3] as $page) {
            $result = Property::filter(['q' => $this->tag])->sortedBy('price_asc')->paginate(2, page: $page);
            $seen = array_merge($seen, collect($result->items())->pluck('id')->all());
        }

        $this->assertCount(6, array_unique($seen));
    }

    public function test_filter_options_list_distinct_cities_and_types(): void
    {
        $this->makeProperty('one', ['type' => 'Zz Unique Type'], ['city' => 'Zz Unique City']);
        $this->makeProperty('two', ['type' => 'Zz Unique Type'], ['city' => 'Zz Unique City']);

        $options = Property::filterOptions();

        $this->assertCount(1, array_keys($options['cities'], 'Zz Unique City'));
        $this->assertCount(1, array_keys($options['types'], 'Zz Unique Type'));
    }
}
