<?php

namespace Tests\Feature;

use App\Models\Property;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\DatabaseTestCase;

class AdminPropertyManagementTest extends DatabaseTestCase
{
    protected function validPayload(array $overrides = []): array
    {
        return array_merge([
            'availability' => 'Available',
            'name' => 'Audit Estate '.Str::random(5),
            'management_company' => 'Audit Management',
            'description' => 'Long description.',

            'address_1' => '12 Mountain Rd',
            'address_2' => '',
            'city' => 'Gatlinburg',
            'state' => 'TN',
            'zip' => '37738',

            'type' => 'Cabin',
            'bedrooms' => 4,
            'baths' => 3,
            'half_baths' => 1,
            'sleeps' => 10,
            'garages' => 2,
            'square_feets' => 3200,
            'stories' => '2-story',
            'units' => 1,
            'lot_size' => 15000.5,
            'year_built' => 2020,
            'zoning' => 'R-1',
            'value' => 850000,

            'offering_purchase' => 650000,
            'offering_build_cost' => 0,
            'offering_improvements' => 25000,
            'offering_closing_cost' => 14000,
            'offering_sourcing_fees' => 18000,

            'property_images' => [UploadedFile::fake()->image('a.jpg', 100, 100)],
        ], $overrides);
    }

    public function test_admin_can_create_property_with_every_field_in_one_form(): void
    {
        $payload = $this->validPayload();

        $this->actingAs($this->admin(), 'admin')->post('/admin/properties', $payload)
            ->assertRedirect(route('admin.properties.index'));

        $property = Property::where('name', $payload['name'])->firstOrFail();
        $this->assertSame('Gatlinburg', $property->city);
        $this->assertNull($property->address_2);
        $this->assertSame(850000, $property->value);
        $this->assertSame(650000, $property->offering_purchase);
        $this->assertCount(1, $property->images);
    }

    public function test_admin_create_property_rejects_invalid_input(): void
    {
        $admin = $this->admin();

        $cases = [
            'empty' => [[], ['availability', 'name', 'description', 'management_company', 'city', 'type', 'value', 'offering_purchase', 'property_images']],
            'whitespace' => [['name' => '   ', 'description' => '   '], ['name', 'description']],
            'name too long' => [['name' => str_repeat('a', 256)], ['name']],
            'company too long' => [['management_company' => str_repeat('a', 256)], ['management_company']],
            'bad availability' => [['availability' => 'Maybe'], ['availability']],
            'array name' => [['name' => ['x']], ['name']],
            'no images' => [['property_images' => null], ['property_images']],
            'pdf as image' => [['property_images' => [UploadedFile::fake()->create('a.pdf', 10, 'application/pdf')]], ['property_images.0']],
            'php renamed jpg' => [['property_images' => [$this->realUpload('shell.jpg', '<?php echo 1;')]], ['property_images.0']],
            'oversize image' => [['property_images' => [UploadedFile::fake()->create('big.jpg', 5000, 'image/jpeg')]], ['property_images.0']],
            'empty image' => [['property_images' => [$this->realUpload('e.jpg', '')]], ['property_images.0']],
            'images not array' => [['property_images' => 'string'], ['property_images']],
            'long address' => [['address_1' => str_repeat('a', 256)], ['address_1']],
            'long city' => [['city' => str_repeat('a', 113)], ['city']],
            'long zip' => [['zip' => str_repeat('1', 21)], ['zip']],
            'bad zip chars' => [['zip' => '<b>'], ['zip']],
            'array city' => [['city' => ['x']], ['city']],
            'negative bedrooms' => [['bedrooms' => -1], ['bedrooms']],
            'huge bedrooms' => [['bedrooms' => 70000], ['bedrooms']],
            'decimal bedrooms' => [['bedrooms' => 2.5], ['bedrooms']],
            'string bedrooms' => [['bedrooms' => 'many'], ['bedrooms']],
            'huge sqft' => [['square_feets' => 99999999999], ['square_feets']],
            'negative value' => [['value' => -1], ['value']],
            'huge value' => [['value' => '99999999999999999999'], ['value']],
            'year too early' => [['year_built' => 1000], ['year_built']],
            'year in far future' => [['year_built' => 9999], ['year_built']],
            'huge lot size' => [['lot_size' => 99999999], ['lot_size']],
            'zero units' => [['units' => 0], ['units']],
            'long zoning' => [['zoning' => str_repeat('a', 256)], ['zoning']],
            'negative offering' => [['offering_purchase' => -1], ['offering_purchase']],
            'fractional offering' => [['offering_build_cost' => 1.5], ['offering_build_cost']],
            'huge offering' => [['offering_closing_cost' => '99999999999999999999999'], ['offering_closing_cost']],
            'text offering' => [['offering_sourcing_fees' => 'abc'], ['offering_sourcing_fees']],
        ];

        foreach ($cases as $label => [$overrides, $errorKeys]) {
            $payload = $overrides === [] ? [] : $this->validPayload($overrides);
            foreach ($overrides as $key => $value) {
                if ($value === null) {
                    unset($payload[$key]);
                }
            }

            $before = Property::count();
            $this->actingAs($admin, 'admin')->from('/admin/properties/create')
                ->post('/admin/properties', $payload)
                ->assertRedirect('/admin/properties/create')
                ->assertSessionHasErrors($errorKeys);
            $this->assertSame($before, Property::count(), "case: {$label} created a record");
        }
    }

    public function test_admin_can_update_property_without_uploading_new_images(): void
    {
        $property = $this->property();

        $this->actingAs($this->admin(), 'admin')
            ->put("/admin/properties/{$property->id}", $this->validPayload([
                'name' => 'Renamed Estate',
                'city' => 'Asheville',
                'value' => 123456,
                'property_images' => null,
            ]))
            ->assertSessionHasNoErrors();

        $property->refresh();
        $this->assertSame('Renamed Estate', $property->name);
        $this->assertSame('Asheville', $property->city);
        $this->assertSame(123456, $property->value);
    }

    public function test_admin_update_appends_images_and_can_delete_one(): void
    {
        $admin = $this->admin();
        $property = $this->property();

        $this->actingAs($admin, 'admin')
            ->put("/admin/properties/{$property->id}", $this->validPayload([
                'property_images' => [UploadedFile::fake()->image('one.jpg'), UploadedFile::fake()->image('two.jpg')],
            ]))
            ->assertSessionHasNoErrors();

        $property->load('images');
        $this->assertCount(2, $property->images);

        $image = $property->images->first();
        Storage::disk('public')->assertExists($image->property_image_value);

        $this->actingAs($admin, 'admin')
            ->delete("/admin/properties/{$property->id}/images/{$image->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('property_images', ['id' => $image->id]);
        Storage::disk('public')->assertMissing($image->property_image_value);
        $this->assertCount(1, $property->images()->get());
    }

    public function test_admin_cannot_remove_the_last_photo(): void
    {
        $admin = $this->admin();
        $property = $this->property();
        $this->actingAs($admin, 'admin')->put("/admin/properties/{$property->id}", $this->validPayload());
        $image = $property->images()->firstOrFail();

        $this->actingAs($admin, 'admin')
            ->delete("/admin/properties/{$property->id}/images/{$image->id}")
            ->assertSessionHasErrors('property_images');

        $this->assertDatabaseHas('property_images', ['id' => $image->id]);
        Storage::disk('public')->assertExists($image->property_image_value);
    }

    public function test_removing_a_photo_keeps_the_file_while_another_row_still_uses_it(): void
    {
        $admin = $this->admin();
        $first = $this->property();
        $second = $this->property();
        Storage::disk('public')->put('property_images/shared.png', 'x');
        foreach ([$first, $second] as $property) {
            $property->images()->createMany([
                ['property_image_key' => 'property_image', 'property_image_value' => 'property_images/shared.png'],
                ['property_image_key' => 'property_image', 'property_image_value' => 'property_images/other-'.$property->id.'.png'],
            ]);
        }

        $image = $first->images()->where('property_image_value', 'property_images/shared.png')->firstOrFail();
        $this->actingAs($admin, 'admin')->delete("/admin/properties/{$first->id}/images/{$image->id}")->assertSessionHasNoErrors();

        Storage::disk('public')->assertExists('property_images/shared.png');
    }

    public function test_admin_cannot_delete_an_image_through_another_property(): void
    {
        $admin = $this->admin();
        $owner = $this->property();
        $other = $this->property();

        $this->actingAs($admin, 'admin')->put("/admin/properties/{$owner->id}", $this->validPayload());
        $image = $owner->images()->firstOrFail();

        $this->actingAs($admin, 'admin')
            ->delete("/admin/properties/{$other->id}/images/{$image->id}")
            ->assertNotFound();

        $this->assertDatabaseHas('property_images', ['id' => $image->id]);
    }

    public function test_admin_update_property_validates_and_missing_records_are_404(): void
    {
        $admin = $this->admin();
        $property = $this->property();

        $this->actingAs($admin, 'admin')->from('/x')
            ->put("/admin/properties/{$property->id}", ['availability' => 'Nope', 'name' => '', 'description' => '', 'management_company' => ''])
            ->assertSessionHasErrors(['availability', 'name', 'description', 'management_company']);

        $this->actingAs($admin, 'admin')->put('/admin/properties/99999999', $this->validPayload())->assertNotFound();
        $this->actingAs($admin, 'admin')->delete('/admin/properties/99999999')->assertNotFound();
        $this->actingAs($admin, 'admin')->get('/admin/properties/abc')->assertNotFound();
        $this->actingAs($admin, 'admin')->get('/admin/users/99999999')->assertNotFound();
    }

    public function test_admin_delete_property_soft_deletes(): void
    {
        $property = $this->property();

        $this->actingAs($this->admin(), 'admin')->delete("/admin/properties/{$property->id}")
            ->assertRedirect();

        $this->assertSoftDeleted('properties', ['id' => $property->id]);
        $this->get('/property/'.$property->slug)->assertNotFound();
    }

    public function test_every_admin_property_page_renders(): void
    {
        $admin = $this->admin();
        $property = $this->property();

        $this->actingAs($admin, 'admin')->get("/admin/properties/{$property->id}")->assertOk();
        $this->actingAs($admin, 'admin')->get("/admin/properties/{$property->id}/edit")->assertOk();
        $this->actingAs($admin, 'admin')->get('/admin/properties/create')->assertOk();
        $this->actingAs($admin, 'admin')->get('/admin/properties')->assertOk();
    }

    public function test_failed_submission_redisplays_what_the_admin_typed(): void
    {
        $admin = $this->admin();
        $property = $this->property();
        $url = "/admin/properties/{$property->id}/edit";

        $this->actingAs($admin, 'admin')->from($url)
            ->put("/admin/properties/{$property->id}", $this->validPayload(['bedrooms' => -7, 'zoning' => 'TYPED-ZONE']))
            ->assertRedirect($url);

        $this->actingAs($admin, 'admin')->get($url)
            ->assertOk()
            ->assertSee('value="-7"', false)
            ->assertSee('value="TYPED-ZONE"', false);
    }

    public function test_property_names_with_quotes_render_safely_in_the_admin_list(): void
    {
        $this->property(['name' => "Bob's \"Lodge\" </script><b>x</b>"]);

        $html = $this->actingAs($this->admin(), 'admin')->get('/admin/properties')->assertOk()->getContent();

        $this->assertStringNotContainsString('</script><b>x</b>', $html);
    }
}
