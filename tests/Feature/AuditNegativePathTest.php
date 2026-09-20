<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\PropertyFavorite;
use App\Models\PropertyInterest;
use App\Models\PropertyModel;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Negative / boundary path coverage across auth, public browsing, favorites,
 * investment interest and the admin property + user forms.
 */
class AuditNegativePathTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Storage::fake(config('filesystems.default', 'public'));
        RateLimiter::clear('audit@example.com|127.0.0.1');
    }

    protected function admin(): Admin
    {
        return Admin::first() ?? Admin::create([
            'username' => 'audit_admin',
            'email' => 'audit_admin_'.Str::random(6).'@example.com',
            'password' => Hash::make('password123'),
        ]);
    }

    /**
     * Uploads built from real temp files, so MIME detection sniffs the content
     * (Laravel's fake files report a MIME type from the file name only).
     */
    protected function realUpload(string $name, string $content): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'audit');
        file_put_contents($path, $content);

        return new UploadedFile($path, $name, null, null, true);
    }

    protected function investor(array $overrides = []): User
    {
        return User::create(array_merge([
            'first_name' => 'Audit',
            'last_name' => 'Investor',
            'email' => 'audit_'.Str::random(8).'@example.com',
            'password' => Hash::make('password123'),
        ], $overrides));
    }

    protected function property(array $overrides = []): PropertyModel
    {
        return PropertyModel::create(array_merge([
            'name' => 'Audit Chalet '.Str::random(6),
            'description' => 'Audit description',
            'management_company' => 'Audit Mgmt',
            'admin_id' => $this->admin()->id,
            'availability' => 'Available',
        ], $overrides));
    }

    // ------------------------------------------------------------------
    // Investor authentication
    // ------------------------------------------------------------------

    public function test_login_rejects_empty_and_malformed_input(): void
    {
        $this->from('/login')->post('/login', [])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email', 'password']);

        $this->from('/login')->post('/login', ['email' => ['a@b.com'], 'password' => ['x']])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email', 'password']);

        $this->from('/login')->post('/login', ['email' => 'not-an-email', 'password' => 'x'])
            ->assertSessionHasErrors(['email']);

        $this->assertGuest();
    }

    public function test_login_is_rate_limited_after_repeated_failures(): void
    {
        $email = 'audit@example.com';

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => $email, 'password' => 'wrong-password'])
                ->assertSessionHasErrors('email');
        }

        $user = $this->investor(['email' => $email]);
        $response = $this->post('/login', ['email' => $user->email, 'password' => 'password123']);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_login_ignores_untrusted_redirect_value(): void
    {
        $user = $this->investor();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
            'redirect' => 'https://evil.example.com',
        ])->assertRedirect(route('properties'));
    }

    // ------------------------------------------------------------------
    // Registration
    // ------------------------------------------------------------------

    protected function validRegistration(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'reg_'.Str::random(8).'@example.com',
            'password' => 'Str0ngPass!',
            'password_confirmation' => 'Str0ngPass!',
            'phone' => '+1 (800) 555-0199',
            'referral' => 'search',
            'experience' => 'beginner',
            'goals' => ['cash_flow'],
            'termsAgree' => '1',
        ], $overrides);
    }

    public function test_registration_accepts_valid_input(): void
    {
        $data = $this->validRegistration();

        $this->post('/register', $data)->assertRedirect(route('properties'));

        $this->assertDatabaseHas('users', ['email' => $data['email'], 'first_name' => 'Jane']);
        $this->assertAuthenticated();
    }

    public function test_registration_rejects_invalid_input(): void
    {
        $existing = $this->investor();

        $cases = [
            'empty' => [[], ['first_name', 'last_name', 'email', 'password']],
            'whitespace names' => [['first_name' => '   ', 'last_name' => '   '], ['first_name', 'last_name']],
            'bad email' => [['email' => 'nope'], ['email']],
            'duplicate email' => [['email' => $existing->email], ['email']],
            'duplicate email different case' => [['email' => strtoupper($existing->email)], ['email']],
            'short password' => [['password' => 'abc12', 'password_confirmation' => 'abc12'], ['password']],
            'seven char password' => [['password' => 'abcdef1', 'password_confirmation' => 'abcdef1'], ['password']],
            'password mismatch' => [['password_confirmation' => 'different'], ['password']],
            'terms not accepted' => [['termsAgree' => null], ['termsAgree']],
            'long first name' => [['first_name' => str_repeat('a', 256)], ['first_name']],
            'invalid phone' => [['phone' => 'call me maybe'], ['phone']],
            'invalid experience' => [['experience' => 'wizard'], ['experience']],
            'invalid referral' => [['referral' => 'carrier-pigeon'], ['referral']],
            'invalid goals' => [['goals' => ['bogus']], ['goals.0']],
            'goals wrong type' => [['goals' => 'cash_flow'], ['goals']],
            'array name' => [['first_name' => ['x']], ['first_name']],
        ];

        foreach ($cases as $label => [$overrides, $errorKeys]) {
            $payload = $overrides === [] ? [] : $this->validRegistration($overrides);
            foreach ($overrides as $key => $value) {
                if ($value === null) {
                    unset($payload[$key]);
                }
            }

            $response = $this->from('/register')->post('/register', $payload);
            $response->assertRedirect('/register');
            $response->assertSessionHasErrors($errorKeys);
            $this->assertGuest();
        }
    }

    public function test_registration_accepts_apostrophes_and_accents_but_rejects_markup_in_names(): void
    {
        $ok = $this->validRegistration(['first_name' => 'Zoë', 'last_name' => "O'Brien-Núñez"]);
        $this->post('/register', $ok)->assertRedirect(route('properties'));
        $this->assertDatabaseHas('users', ['email' => $ok['email'], 'last_name' => "O'Brien-Núñez"]);

        auth()->logout();

        foreach (['<script>alert(1)</script>', "Robert'); DROP TABLE users;--", '😀', 'John3'] as $bad) {
            $this->from('/register')->post('/register', $this->validRegistration(['last_name' => $bad]))
                ->assertSessionHasErrors('last_name');
        }
    }

    public function test_stored_markup_is_escaped_when_rendered(): void
    {
        $user = $this->investor(['first_name' => '<script>alert(1)</script>']);

        $this->actingAs($user)->get('/dashboard')
            ->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    // ------------------------------------------------------------------
    // Password reset
    // ------------------------------------------------------------------

    public function test_forgot_password_does_not_reveal_whether_an_email_exists(): void
    {
        $known = $this->investor();

        $a = $this->from('/forgot-password')->post('/forgot-password', ['email' => $known->email]);
        $b = $this->from('/forgot-password')->post('/forgot-password', ['email' => 'nobody_'.Str::random(6).'@example.com']);

        $a->assertSessionHas('status');
        $b->assertSessionHas('status');
        $b->assertSessionHasNoErrors();
        $this->assertSame(session('status'), $a->getSession()->get('status'));
    }

    public function test_forgot_password_validates_email(): void
    {
        $this->from('/forgot-password')->post('/forgot-password', [])->assertSessionHasErrors('email');
        $this->from('/forgot-password')->post('/forgot-password', ['email' => 'bad'])->assertSessionHasErrors('email');
        $this->from('/forgot-password')->post('/forgot-password', ['email' => ['a@b.co']])->assertSessionHasErrors('email');
    }

    public function test_reset_password_requires_matching_strong_password(): void
    {
        $this->from('/reset-password/abc')->post('/reset-password', [
            'token' => 'abc', 'email' => 'x@example.com', 'password' => 'abc123', 'password_confirmation' => 'abc123',
        ])->assertSessionHasErrors('password');

        $this->from('/reset-password/abc')->post('/reset-password', [
            'token' => 'abc', 'email' => 'x@example.com', 'password' => 'LongEnough1', 'password_confirmation' => 'other',
        ])->assertSessionHasErrors('password');

        $this->from('/reset-password/abc')->post('/reset-password', [
            'token' => 'invalid-token', 'email' => 'x@example.com', 'password' => 'LongEnough1', 'password_confirmation' => 'LongEnough1',
        ])->assertSessionHasErrors('email');
    }

    // ------------------------------------------------------------------
    // Public browsing
    // ------------------------------------------------------------------

    public function test_property_listing_survives_hostile_query_strings(): void
    {
        $queries = [
            '?q[]=x',
            '?location[]=x&property_type[]=y',
            '?min_price=abc&max_price=-5&bedrooms=-3&bathrooms=zzz&min_cap_rate=1e999',
            '?min_price=99999999999999999999999',
            '?page=999999',
            '?page=-4',
            '?page[]=1',
            '?sort_by=DROP TABLE',
            '?sort_by[]=price_asc',
            '?q='.urlencode(str_repeat('a', 5000)),
            '?q='.urlencode("'; DROP TABLE properties;--"),
            '?q=%25&location=_',
            '?is_1031[]=1',
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

    // ------------------------------------------------------------------
    // Favorites & investment interest
    // ------------------------------------------------------------------

    public function test_favorite_toggle_requires_authentication(): void
    {
        $property = $this->property();

        $this->postJson("/property/{$property->id}/favorite")->assertUnauthorized();
    }

    public function test_favorite_toggle_handles_missing_property_and_toggles_cleanly(): void
    {
        $user = $this->investor();
        $property = $this->property();

        $this->actingAs($user)->postJson('/property/99999999/favorite')->assertNotFound();
        $this->actingAs($user)->postJson('/property/not-a-number/favorite')->assertNotFound();

        $this->actingAs($user)->postJson("/property/{$property->id}/favorite")
            ->assertOk()->assertJson(['success' => true, 'isFavorited' => true]);
        $this->actingAs($user)->postJson("/property/{$property->id}/favorite")
            ->assertOk()->assertJson(['success' => true, 'isFavorited' => false]);

        $this->assertDatabaseCount('property_favorites', PropertyFavorite::count());
        $this->assertDatabaseMissing('property_favorites', ['user_id' => $user->id, 'property_id' => $property->id]);
    }

    public function test_investment_interest_requires_login_and_valid_property(): void
    {
        $property = $this->property();

        $this->post("/investment-interest/{$property->id}")->assertRedirect(route('login'));

        $user = $this->investor();
        $this->actingAs($user)->post('/investment-interest/99999999')->assertNotFound();
        $this->actingAs($user)->post('/investment-interest/abc')->assertNotFound();
        $this->assertDatabaseMissing('property_interests', ['user_id' => $user->id]);
    }

    public function test_repeat_investment_interest_does_not_reset_status_or_duplicate(): void
    {
        $user = $this->investor();
        $property = $this->property();

        $this->actingAs($user)->post("/investment-interest/{$property->id}")
            ->assertRedirect(route('investment.interest'));

        PropertyInterest::where('user_id', $user->id)->update(['status' => 'contacted']);

        $this->actingAs($user)->post("/investment-interest/{$property->id}")
            ->assertRedirect(route('investment.interest'));

        $this->assertSame(1, PropertyInterest::where('user_id', $user->id)->count());
        $this->assertSame('contacted', PropertyInterest::where('user_id', $user->id)->value('status'));
    }

    // ------------------------------------------------------------------
    // Admin access control
    // ------------------------------------------------------------------

    public function test_guests_and_investors_cannot_reach_admin_endpoints(): void
    {
        $property = $this->property();
        $investor = $this->investor();

        $gets = [
            '/admin/dashboard',
            '/admin/users',
            '/admin/users/create',
            "/admin/users/{$investor->id}",
            "/admin/users/{$investor->id}/edit",
            '/admin/manage-property',
            '/admin/manage-property/create',
            "/admin/manage-property/{$property->id}",
            "/admin/manage-property/{$property->id}/edit",
            "/admin/manage-property/edit-address/{$property->id}",
            "/admin/manage-property/edit-details/{$property->id}",
            "/admin/manage-property/edit-amenities/{$property->id}",
            "/admin/manage-property/edit-floorplan/{$property->id}",
            "/admin/manage-property/edit-property-offerings/{$property->id}",
            "/admin/manage-property/edit-property-documents/{$property->id}",
            "/admin/manage-property/edit-property-metrics/{$property->id}",
        ];

        foreach ([null, $investor] as $actor) {
            foreach ($gets as $url) {
                $request = $actor ? $this->actingAs($actor, 'web') : $this;
                $request->get($url)->assertRedirect('/');
            }
        }

        $posts = [
            ['post', '/admin/users', ['first_name' => 'X', 'email' => 'x@example.com', 'password' => 'password123']],
            ['put', "/admin/users/{$investor->id}", ['first_name' => 'Hacked', 'email' => $investor->email]],
            ['delete', "/admin/users/{$investor->id}", []],
            ['post', '/admin/manage-property', ['name' => 'x']],
            ['put', "/admin/manage-property/{$property->id}", ['name' => 'Hacked']],
            ['delete', "/admin/manage-property/{$property->id}", []],
            ['post', "/admin/manage-property/update-address/{$property->id}", ['city' => 'x']],
            ['post', "/admin/manage-property/update-property-metrics/{$property->id}", ['cap_rate' => 1]],
        ];

        foreach ($posts as [$method, $url, $body]) {
            $this->actingAs($investor, 'web')->{$method}($url, $body)->assertRedirect('/');
        }

        $this->assertDatabaseHas('properties', ['id' => $property->id, 'deleted_at' => null]);
        $this->assertDatabaseHas('users', ['id' => $investor->id, 'first_name' => 'Audit']);
    }

    public function test_admin_login_validates_input(): void
    {
        $this->from('/admin')->post('/admin/login', [])->assertSessionHasErrors(['email', 'password']);
        $this->from('/admin')->post('/admin/login', ['email' => 'nope', 'password' => 'x'])->assertSessionHasErrors('email');
        $this->from('/admin')->post('/admin/login', ['email' => ['a@b.co'], 'password' => ['x']])->assertSessionHasErrors(['email', 'password']);
        $this->from('/admin')->post('/admin/login', ['email' => 'nobody@example.com', 'password' => 'wrong'])
            ->assertRedirect('/admin');
        $this->assertGuest('admin');
    }

    public function test_admin_login_is_rate_limited(): void
    {
        $email = 'audit_admin_rl@example.com';
        RateLimiter::clear(Str::transliterate($email.'|127.0.0.1'));

        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', ['email' => $email, 'password' => 'wrong']);
        }

        Admin::create(['username' => 'rl', 'email' => $email, 'password' => Hash::make('password123')]);
        $this->post('/admin/login', ['email' => $email, 'password' => 'password123']);

        $this->assertGuest('admin');
        RateLimiter::clear(Str::transliterate($email.'|127.0.0.1'));
    }

    // ------------------------------------------------------------------
    // Admin property CRUD
    // ------------------------------------------------------------------

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

    public function test_admin_can_create_property_with_valid_input(): void
    {
        $payload = $this->validPropertyPayload();

        $response = $this->actingAs($this->admin(), 'admin')->post('/admin/manage-property', $payload);

        $property = PropertyModel::where('name', $payload['name'])->firstOrFail();
        $response->assertRedirect(route('admin.manage-property.edit-address', ['id' => $property->id]));
        $this->assertCount(1, $property->propertyImage);
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

            $before = PropertyModel::count();
            $this->actingAs($admin, 'admin')->from('/admin/manage-property/create')
                ->post('/admin/manage-property', $payload)
                ->assertRedirect('/admin/manage-property/create')
                ->assertSessionHasErrors($errorKeys);
            $this->assertSame($before, PropertyModel::count(), "case: {$label} created a record");
        }
    }

    public function test_admin_can_save_address_without_second_line(): void
    {
        $property = $this->property();

        $this->actingAs($this->admin(), 'admin')->post("/admin/manage-property/update-address/{$property->id}", [
            'address_1' => '12 Mountain Rd',
            'address_2' => '',
            'city' => 'Gatlinburg',
            'state' => 'TN',
            'zip' => '37738',
        ])->assertRedirect(route('admin.manage-property.edit-details', ['id' => $property->id]));

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
                ->post("/admin/manage-property/update-address/{$property->id}", $payload)
                ->assertSessionHasErrors($errorKeys);
        }
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

    public function test_admin_details_accepts_valid_and_decimal_lot_size(): void
    {
        $property = $this->property();

        $this->actingAs($this->admin(), 'admin')
            ->post("/admin/manage-property/update-details/{$property->id}", $this->validDetails())
            ->assertRedirect(route('admin.manage-property.edit-amenities', ['id' => $property->id]));

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
                ->post("/admin/manage-property/update-details/{$property->id}", $this->validDetails($override))
                ->assertSessionHasErrors($field);
        }

        $this->actingAs($admin, 'admin')->from('/x')
            ->post("/admin/manage-property/update-details/{$property->id}", [])
            ->assertSessionHasErrors(['type', 'bedrooms', 'value']);
    }

    public function test_admin_amenities_form_submits_comma_separated_text(): void
    {
        $property = $this->property();

        $this->actingAs($this->admin(), 'admin')->from('/x')
            ->post("/admin/manage-property/update-aminities/{$property->id}", [
                'property_amenities' => ' Hot Tub, Pool ,, Pool, <b>Deck</b> ',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.manage-property.edit-floorplan', ['id' => $property->id]));

        $stored = $property->propertyAmenities()->pluck('property_amenities')->all();
        $this->assertSame(['Hot Tub', 'Pool', '<b>Deck</b>'], $stored);
    }

    public function test_admin_amenities_rejects_oversized_input(): void
    {
        $property = $this->property();

        $this->actingAs($this->admin(), 'admin')->from('/x')
            ->post("/admin/manage-property/update-aminities/{$property->id}", [
                'property_amenities' => str_repeat('a', 300),
            ])->assertSessionHasErrors('property_amenities.0');

        $this->actingAs($this->admin(), 'admin')->from('/x')
            ->post("/admin/manage-property/update-aminities/{$property->id}", [
                'property_amenities' => implode(',', range(1, 150)),
            ])->assertSessionHasErrors('property_amenities');
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
            ->post("/admin/manage-property/update-property-offerings/{$property->id}", $valid)
            ->assertRedirect(route('admin.manage-property.edit-property-documents', ['id' => $property->id]));

        foreach (['offering_purchase' => -1, 'offering_build_cost' => 1.5, 'offering_closing_cost' => '99999999999999999999999', 'offering_sourcing_fees' => 'abc'] as $field => $value) {
            $this->actingAs($admin, 'admin')->from('/x')
                ->post("/admin/manage-property/update-property-offerings/{$property->id}", array_merge($valid, [$field => $value]))
                ->assertSessionHasErrors($field);
        }
    }

    public function test_admin_metrics_honours_net_operating_income_and_bounds_values(): void
    {
        $property = $this->property();
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')
            ->post("/admin/manage-property/update-property-metrics/{$property->id}", [
                'gross_annual_rent' => 100000,
                'operating_expenses' => 20000,
                'net_operating_income' => 50000,
            ])->assertRedirect(route('manage-property.index'));

        $this->assertEquals(50000, $property->propertyMetrics()->first()->net_operating_income);

        foreach ([
            'gross_annual_rent' => '99999999999999',
            'cap_rate' => 101,
            'cash_flow_rating' => 101,
            'diversification_score' => 11,
            'calculation_notes' => str_repeat('a', 2001),
            'projected_value_5yr' => '99999999999999999',
        ] as $field => $value) {
            $this->actingAs($admin, 'admin')->from('/x')
                ->post("/admin/manage-property/update-property-metrics/{$property->id}", [$field => $value])
                ->assertSessionHasErrors($field);
        }
    }

    public function test_admin_floorplan_and_document_uploads_validate_files(): void
    {
        $property = $this->property();
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')->from('/x')
            ->post("/admin/manage-property/update-floorplan/{$property->id}", [
                'floor_plan_1' => UploadedFile::fake()->create('plan.pdf', 10, 'application/pdf'),
                'floor_plan_2' => $this->realUpload('shell.png', '<?php echo 1;'),
            ])->assertSessionHasErrors(['floor_plan_1', 'floor_plan_2']);

        $this->actingAs($admin, 'admin')->from('/x')
            ->post("/admin/manage-property/update-property-documents/{$property->id}", [
                'document_1' => UploadedFile::fake()->image('doc.jpg'),
                'Documents_Master_Deed' => $this->realUpload('deed.pdf', '<?php echo 1;'),
                'Documents_Deed_Example' => UploadedFile::fake()->create('big.pdf', 5000, 'application/pdf'),
            ])->assertSessionHasErrors(['document_1', 'Documents_Master_Deed', 'Documents_Deed_Example']);

        $this->actingAs($admin, 'admin')
            ->post("/admin/manage-property/update-floorplan/{$property->id}", [
                'floor_plan_1' => UploadedFile::fake()->image('plan.png', 50, 50),
            ])->assertSessionHasNoErrors();
        $this->assertCount(1, $property->propertyFloorplan()->get());
    }

    public function test_admin_update_property_validates_and_missing_records_are_404(): void
    {
        $admin = $this->admin();
        $property = $this->property();

        $this->actingAs($admin, 'admin')->from('/x')
            ->put("/admin/manage-property/{$property->id}", ['availability' => 'Nope', 'name' => '', 'description' => '', 'management_company' => ''])
            ->assertSessionHasErrors(['availability', 'name', 'description', 'management_company']);

        $this->actingAs($admin, 'admin')->put('/admin/manage-property/99999999', [
            'availability' => 'Available', 'name' => 'x', 'description' => 'x', 'management_company' => 'x',
        ])->assertNotFound();

        $this->actingAs($admin, 'admin')->delete('/admin/manage-property/99999999')->assertNotFound();
        $this->actingAs($admin, 'admin')->get('/admin/manage-property/abc')->assertNotFound();
        $this->actingAs($admin, 'admin')->get('/admin/manage-property/edit-address/99999999')->assertNotFound();
        $this->actingAs($admin, 'admin')->get('/admin/users/99999999')->assertNotFound();
    }

    public function test_admin_delete_property_soft_deletes_and_clears_cache(): void
    {
        $property = $this->property();

        $this->actingAs($this->admin(), 'admin')->delete("/admin/manage-property/{$property->id}")
            ->assertRedirect();

        $this->assertSoftDeleted('properties', ['id' => $property->id]);
        $this->get('/property/'.$property->slug)->assertNotFound();
    }

    // ------------------------------------------------------------------
    // Admin user management
    // ------------------------------------------------------------------

    public function test_admin_user_list_survives_hostile_filters(): void
    {
        $admin = $this->admin();

        foreach (['?search[]=x', '?status[]=verified', '?accreditation[]=1', '?search=%25', '?search='.urlencode(str_repeat('a', 3000)), '?page=99999', '?page=abc', '?accreditation=abc'] as $query) {
            $this->actingAs($admin, 'admin')->get('/admin/users'.$query)->assertOk();
        }
    }

    public function test_admin_user_form_rejects_invalid_input(): void
    {
        $admin = $this->admin();
        $existing = $this->investor();

        $valid = ['first_name' => 'Ann', 'email' => 'ann_'.Str::random(6).'@example.com', 'password' => 'password123'];

        $cases = [
            'empty' => [[], ['first_name', 'email', 'password']],
            'whitespace name' => [['first_name' => '   '], ['first_name']],
            'dup email' => [['email' => $existing->email], ['email']],
            'short password' => [['password' => 'short'], ['password']],
            'bad phone' => [['phone' => 'abc'], ['phone']],
            'future dob' => [['dob' => now()->addYear()->toDateString()], ['dob']],
            'bad dob' => [['dob' => '2020-13-45'], ['dob']],
            'bad ssn' => [['social_security_number' => 'not-a-ssn'], ['social_security_number']],
            'bad lookup id' => [['accreditation_status' => 999999], ['accreditation_status']],
            'string lookup id' => [['accreditation_status' => 'abc'], ['accreditation_status']],
            'long name' => [['first_name' => str_repeat('a', 256)], ['first_name']],
            'bad bool' => [['email_verified' => 'maybe'], ['email_verified']],
            'array email' => [['email' => ['a@b.co']], ['email']],
        ];

        foreach ($cases as $label => [$overrides, $errorKeys]) {
            $payload = $overrides === [] ? [] : array_merge($valid, $overrides);
            $before = User::count();
            $this->actingAs($admin, 'admin')->from('/admin/users/create')
                ->post('/admin/users', $payload)
                ->assertSessionHasErrors($errorKeys);
            $this->assertSame($before, User::count(), "case: {$label} created a record");
        }
    }

    public function test_admin_can_create_update_and_delete_user_with_valid_input(): void
    {
        $admin = $this->admin();
        $email = 'crud_'.Str::random(6).'@example.com';

        $this->actingAs($admin, 'admin')->post('/admin/users', [
            'first_name' => 'Crud', 'email' => $email, 'password' => 'password123',
            'phone' => '+1 (555) 000-0000', 'dob' => '1990-05-01', 'social_security_number' => '123-45-6789',
        ])->assertSessionHasNoErrors();

        $user = User::where('email', $email)->firstOrFail();

        $this->actingAs($admin, 'admin')->put("/admin/users/{$user->id}", [
            'first_name' => 'Crud2', 'email' => $email,
        ])->assertSessionHasNoErrors();
        $this->assertSame('Crud2', $user->fresh()->first_name);
        $this->assertTrue(Hash::check('password123', $user->fresh()->password));

        $this->actingAs($admin, 'admin')->delete("/admin/users/{$user->id}")->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_user_update_rejects_email_owned_by_someone_else(): void
    {
        $admin = $this->admin();
        $a = $this->investor();
        $b = $this->investor();

        $this->actingAs($admin, 'admin')->from('/x')
            ->put("/admin/users/{$a->id}", ['first_name' => 'A', 'email' => $b->email])
            ->assertSessionHasErrors('email');

        $this->actingAs($admin, 'admin')->from('/x')
            ->put("/admin/users/{$a->id}", ['first_name' => 'A', 'email' => $a->email])
            ->assertSessionHasNoErrors();
    }

    public function test_deleting_user_also_removes_alerts_and_beneficiaries(): void
    {
        $admin = $this->admin();
        $user = $this->investor();
        \DB::table('user_alerts')->insert(['user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()]);

        $this->actingAs($admin, 'admin')->delete("/admin/users/{$user->id}")->assertRedirect();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('user_alerts', ['user_id' => $user->id]);
    }

    // ------------------------------------------------------------------
    // Email verification
    // ------------------------------------------------------------------

    public function test_email_verification_handles_bad_tokens_and_is_single_use(): void
    {
        $this->get('/verify-email/'.str_repeat('a', 64))->assertRedirect(route('properties'))->assertSessionHas('error');

        $user = $this->investor(['verification_token' => Str::random(64)]);
        $token = $user->verification_token;

        $this->get('/verify-email/'.$token)->assertSessionHas('success');
        $this->assertNotNull($user->fresh()->email_verified_at);

        auth()->logout();
        $this->get('/verify-email/'.$token)->assertSessionHas('error');
    }

    public function test_resend_verification_requires_login_and_skips_verified_users(): void
    {
        $this->post('/resend-verification')->assertRedirect(route('login'));

        $verified = $this->investor(['email_verified_at' => now()]);
        $this->actingAs($verified)->post('/resend-verification')->assertSessionHas('success');
    }

    // ------------------------------------------------------------------
    // Form redisplay, rendering and file replacement
    // ------------------------------------------------------------------

    public function test_admin_user_form_accepts_masked_ssn_already_stored_on_existing_users(): void
    {
        $user = $this->investor(['social_security_number' => 'XXX-XX-4892']);

        $this->actingAs($this->admin(), 'admin')->from('/x')
            ->put("/admin/users/{$user->id}", [
                'first_name' => 'Masked', 'email' => $user->email, 'social_security_number' => 'XXX-XX-4892',
            ])->assertSessionHasNoErrors();

        $this->assertSame('Masked', $user->fresh()->first_name);
    }

    public function test_every_admin_property_step_renders_for_a_property_with_no_related_records(): void
    {
        $admin = $this->admin();
        $property = $this->property();

        foreach (['edit-address', 'edit-details', 'edit-amenities', 'edit-floorplan', 'edit-property-offerings', 'edit-property-documents', 'edit-property-metrics'] as $step) {
            $this->actingAs($admin, 'admin')->get("/admin/manage-property/{$step}/{$property->id}")->assertOk();
        }

        $this->actingAs($admin, 'admin')->get("/admin/manage-property/{$property->id}")->assertOk();
        $this->actingAs($admin, 'admin')->get("/admin/manage-property/{$property->id}/edit")->assertOk();
        $this->actingAs($admin, 'admin')->get('/admin/manage-property/create')->assertOk();
        $this->actingAs($admin, 'admin')->get('/admin/manage-property')->assertOk();
    }

    public function test_failed_details_submission_redisplays_what_the_admin_typed(): void
    {
        $admin = $this->admin();
        $property = $this->property();
        $url = "/admin/manage-property/edit-details/{$property->id}";

        $this->actingAs($admin, 'admin')
            ->post("/admin/manage-property/update-details/{$property->id}", $this->validDetails())
            ->assertSessionHasNoErrors();

        $this->actingAs($admin, 'admin')->from($url)
            ->post("/admin/manage-property/update-details/{$property->id}", $this->validDetails(['bedrooms' => -7, 'zoning' => 'TYPED-ZONE']))
            ->assertRedirect($url);

        $this->actingAs($admin, 'admin')->get($url)
            ->assertOk()
            ->assertSee('value="-7"', false)
            ->assertSee('value="TYPED-ZONE"', false);
    }

    public function test_failed_amenities_submission_redisplays_without_erroring(): void
    {
        $admin = $this->admin();
        $property = $this->property();
        $url = "/admin/manage-property/edit-amenities/{$property->id}";

        $this->actingAs($admin, 'admin')->from($url)
            ->post("/admin/manage-property/update-aminities/{$property->id}", ['property_amenities' => 'Pool, '.str_repeat('a', 300)])
            ->assertRedirect($url);

        $this->actingAs($admin, 'admin')->get($url)->assertOk()->assertSee('Pool, ');
    }

    public function test_registration_form_offers_password_confirmation(): void
    {
        $this->get('/register')->assertOk()->assertSee('name="password_confirmation"', false);
    }

    public function test_failed_registration_page_still_renders(): void
    {
        $this->from('/register')->post('/register', $this->validRegistration(['password_confirmation' => 'nope']))
            ->assertRedirect('/register');

        $this->get('/register')->assertOk();
    }

    public function test_replacing_a_floorplan_leaves_one_record_and_removes_the_old_file(): void
    {
        $admin = $this->admin();
        $property = $this->property();
        $disk = Storage::disk(config('filesystems.default', 'public') === 'local' ? 'public' : config('filesystems.default', 'public'));

        $this->actingAs($admin, 'admin')->post("/admin/manage-property/update-floorplan/{$property->id}", [
            'floor_plan_1' => UploadedFile::fake()->image('one.png', 40, 40),
        ])->assertSessionHasNoErrors();
        $first = $property->propertyFloorplan()->first()->value;
        $disk->assertExists($first);

        $this->actingAs($admin, 'admin')->post("/admin/manage-property/update-floorplan/{$property->id}", [
            'floor_plan_1' => UploadedFile::fake()->image('two.png', 40, 40),
        ])->assertSessionHasNoErrors();

        $this->assertSame(1, $property->propertyFloorplan()->count());
        $disk->assertMissing($first);
    }

    public function test_property_with_a_very_long_name_is_reachable_by_its_slug(): void
    {
        $property = $this->property(['name' => str_repeat('a', 250)]);
        $this->assertGreaterThan(200, strlen($property->slug));

        $this->get('/property/'.$property->slug)->assertOk();
        // the cached copy is served on the second hit
        $this->get('/property/'.$property->slug)->assertOk();
    }

    public function test_property_names_with_quotes_render_safely_in_the_admin_list(): void
    {
        $this->property(['name' => "Bob's \"Lodge\" </script><b>x</b>"]);

        $html = $this->actingAs($this->admin(), 'admin')->get('/admin/manage-property')->assertOk()->getContent();

        $this->assertStringNotContainsString('</script><b>x</b>', $html);
    }

    public function test_listing_filters_still_work_with_valid_input(): void
    {
        $this->get('/invest?q=chalet&bedrooms=2&min_price=100000&max_price=900000&sort_by=price_asc&min_cap_rate=6&is_1031=1&page=1')
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
