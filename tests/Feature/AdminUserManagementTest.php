<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\DatabaseTestCase;

class AdminUserManagementTest extends DatabaseTestCase
{
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

    public function test_admin_user_form_accepts_masked_ssn_already_stored_on_existing_users(): void
    {
        $user = $this->investor(['social_security_number' => 'XXX-XX-4892']);

        $this->actingAs($this->admin(), 'admin')->from('/x')
            ->put("/admin/users/{$user->id}", [
                'first_name' => 'Masked', 'email' => $user->email, 'social_security_number' => 'XXX-XX-4892',
            ])->assertSessionHasNoErrors();

        $this->assertSame('Masked', $user->fresh()->first_name);
    }
}
