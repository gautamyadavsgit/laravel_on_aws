<?php

namespace Tests\Feature;

use App\Models\Property;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\DatabaseTestCase;

class AdminPropertyManagementTest extends DatabaseTestCase
{
    protected function validPropertyPayload(array $overrides = []): array
    {
        return array_merge([
            'availability' => 'Available',
            'name' => 'Audit Estate '.Str::random(5),
            'management_company' => 'Audit Management',
            'description' => 'Long description.',
            'property_images' => [UploadedFile::fake()->image('a.jpg', 100, 100)],
        ], $overrides);
    }

    protected function validDetails(array $overrides = []): array
    {
        return array_merge([
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
        ], $overrides);
    }

    public function test_admin_can_create_property_with_valid_input(): void
    {
        $payload = $this->validPropertyPayload();

        $response = $this->actingAs($this->admin(), 'admin')->post('/admin/properties', $payload);

        $property = Property::where('name', $payload['name'])->firstOrFail();
        $response->assertRedirect(route('admin.properties.address.edit', $property));
        $this->assertCount(1, $property->images);
    }

    public function test_admin_create_property_rejects_invalid_input(): void
    {
        $admin = $this->admin();

        $cases = [
            'empty' => [[], ['availability', 'name', 'description', 'management_company', 'property_images']],
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
        ];

        foreach ($cases as $label => [$overrides, $errorKeys]) {
            $payload = $overrides === [] ? [] : $this->validPropertyPayload($overrides);
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

    public function test_admin_can_save_address_without_second_line(): void
    {
        $property = $this->property();

        $this->actingAs($this->admin(), 'admin')->put("/admin/properties/{$property->id}/address", [
            'address_1' => '12 Mountain Rd',
            'address_2' => '',
            'city' => 'Gatlinburg',
            'state' => 'TN',
            'zip' => '37738',
        ])->assertRedirect(route('admin.properties.details.edit', $property));

        $this->assertDatabaseHas('property_address', ['property_id' => $property->id, 'city' => 'Gatlinburg', 'address_2' => null]);
    }

    public function test_admin_address_rejects_invalid_input(): void
    {
        $property = $this->property();
        $admin = $this->admin();
        $valid = ['address_1' => '1 Main', 'address_2' => '', 'city' => 'Town', 'state' => 'TN', 'zip' => '37738'];

        $cases = [
            'empty' => [[], ['address_1', 'city', 'state', 'zip']],
            'long address' => [['address_1' => str_repeat('a', 256)], ['address_1']],
            'long city' => [['city' => str_repeat('a', 113)], ['city']],
            'long state' => [['state' => str_repeat('a', 113)], ['state']],
            'long zip' => [['zip' => str_repeat('1', 21)], ['zip']],
            'array city' => [['city' => ['x']], ['city']],
            'whitespace city' => [['city' => '   '], ['city']],
        ];

        foreach ($cases as $label => [$overrides, $errorKeys]) {
            $payload = $overrides === [] ? [] : array_merge($valid, $overrides);
            $this->actingAs($admin, 'admin')->from('/x')
                ->put("/admin/properties/{$property->id}/address", $payload)
                ->assertSessionHasErrors($errorKeys);
        }
    }

    public function test_admin_details_accepts_valid_and_decimal_lot_size(): void
    {
        $property = $this->property();

        $this->actingAs($this->admin(), 'admin')
            ->put("/admin/properties/{$property->id}/details", $this->validDetails())
            ->assertRedirect(route('admin.properties.offering.edit', $property));

        $this->assertDatabaseHas('property_details', ['property_id' => $property->id, 'bedrooms' => 4, 'value' => 850000, 'zoning' => 'R-1']);
    }

    public function test_admin_details_rejects_out_of_range_values_instead_of_erroring(): void
    {
        $property = $this->property();
        $admin = $this->admin();

        $cases = [
            'negative bedrooms' => ['bedrooms' => -1],
            'huge bedrooms' => ['bedrooms' => 70000],
            'negative sqft' => ['square_feets' => -5],
            'huge sqft' => ['square_feets' => 99999999999],
            'negative value' => ['value' => -1],
            'huge value' => ['value' => '99999999999999999999'],
            'decimal bedrooms' => ['bedrooms' => 2.5],
            'string bedrooms' => ['bedrooms' => 'many'],
            'year too early' => ['year_built' => 1000],
            'year in far future' => ['year_built' => 9999],
            'year 3 digits' => ['year_built' => 999],
            'huge lot size' => ['lot_size' => 99999999],
            'negative lot size' => ['lot_size' => -1],
            'zero units' => ['units' => 0],
            'long type' => ['type' => str_repeat('a', 256)],
            'long zoning' => ['zoning' => str_repeat('a', 256)],
            'long stories' => ['stories' => str_repeat('a', 256)],
            'array type' => ['type' => ['x']],
        ];

        foreach ($cases as $label => $override) {
            $field = array_key_first($override);
            $this->actingAs($admin, 'admin')->from('/x')
                ->put("/admin/properties/{$property->id}/details", $this->validDetails($override))
                ->assertSessionHasErrors($field);
        }

        $this->actingAs($admin, 'admin')->from('/x')
            ->put("/admin/properties/{$property->id}/details", [])
            ->assertSessionHasErrors(['type', 'bedrooms', 'value']);
    }

    public function test_admin_offerings_reject_negative_fractional_and_huge_values(): void
    {
        $property = $this->property();
        $admin = $this->admin();
        $valid = [
            'offering_purchase' => 650000,
            'offering_build_cost' => 0,
            'offering_improvements' => 25000,
            'offering_closing_cost' => 14000,
            'offering_sourcing_fees' => 18000,
        ];

        $this->actingAs($admin, 'admin')
            ->put("/admin/properties/{$property->id}/offering", $valid)
            ->assertRedirect(route('admin.properties.documents.edit', $property));

        foreach (['offering_purchase' => -1, 'offering_build_cost' => 1.5, 'offering_closing_cost' => '99999999999999999999999', 'offering_sourcing_fees' => 'abc'] as $field => $value) {
            $this->actingAs($admin, 'admin')->from('/x')
                ->put("/admin/properties/{$property->id}/offering", array_merge($valid, [$field => $value]))
                ->assertSessionHasErrors($field);
        }
    }

    public function test_admin_document_uploads_validate_files(): void
    {
        $property = $this->property();
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')->from('/x')
            ->put("/admin/properties/{$property->id}/documents", [
                'document_1' => UploadedFile::fake()->image('doc.jpg'),
                'Documents_Master_Deed' => $this->realUpload('deed.pdf', '<?php echo 1;'),
                'Documents_Deed_Example' => UploadedFile::fake()->create('big.pdf', 5000, 'application/pdf'),
            ])->assertSessionHasErrors(['document_1', 'Documents_Master_Deed', 'Documents_Deed_Example']);
    }

    public function test_admin_update_property_validates_and_missing_records_are_404(): void
    {
        $admin = $this->admin();
        $property = $this->property();

        $this->actingAs($admin, 'admin')->from('/x')
            ->put("/admin/properties/{$property->id}", ['availability' => 'Nope', 'name' => '', 'description' => '', 'management_company' => ''])
            ->assertSessionHasErrors(['availability', 'name', 'description', 'management_company']);

        $this->actingAs($admin, 'admin')->put('/admin/properties/99999999', [
            'availability' => 'Available', 'name' => 'x', 'description' => 'x', 'management_company' => 'x',
        ])->assertNotFound();

        $this->actingAs($admin, 'admin')->delete('/admin/properties/99999999')->assertNotFound();
        $this->actingAs($admin, 'admin')->get('/admin/properties/abc')->assertNotFound();
        $this->actingAs($admin, 'admin')->get('/admin/properties/99999999/address')->assertNotFound();
        $this->actingAs($admin, 'admin')->get('/admin/users/99999999')->assertNotFound();
    }

    public function test_admin_delete_property_soft_deletes_and_clears_cache(): void
    {
        $property = $this->property();

        $this->actingAs($this->admin(), 'admin')->delete("/admin/properties/{$property->id}")
            ->assertRedirect();

        $this->assertSoftDeleted('properties', ['id' => $property->id]);
        $this->get('/property/'.$property->slug)->assertNotFound();
    }

    public function test_every_admin_property_step_renders_for_a_property_with_no_related_records(): void
    {
        $admin = $this->admin();
        $property = $this->property();

        foreach (['address', 'details', 'offering', 'documents'] as $step) {
            $this->actingAs($admin, 'admin')->get("/admin/properties/{$property->id}/{$step}")->assertOk();
        }

        $this->actingAs($admin, 'admin')->get("/admin/properties/{$property->id}")->assertOk();
        $this->actingAs($admin, 'admin')->get("/admin/properties/{$property->id}/edit")->assertOk();
        $this->actingAs($admin, 'admin')->get('/admin/properties/create')->assertOk();
        $this->actingAs($admin, 'admin')->get('/admin/properties')->assertOk();
    }

    public function test_failed_details_submission_redisplays_what_the_admin_typed(): void
    {
        $admin = $this->admin();
        $property = $this->property();
        $url = "/admin/properties/{$property->id}/details";

        $this->actingAs($admin, 'admin')
            ->put("/admin/properties/{$property->id}/details", $this->validDetails())
            ->assertSessionHasNoErrors();

        $this->actingAs($admin, 'admin')->from($url)
            ->put("/admin/properties/{$property->id}/details", $this->validDetails(['bedrooms' => -7, 'zoning' => 'TYPED-ZONE']))
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
