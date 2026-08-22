<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminUserCrudTest extends TestCase
{
    protected function getOrCreateAdmin(): Admin
    {
        $admin = Admin::first();
        if (! $admin) {
            $admin = Admin::create([
                'name' => 'Test Admin',
                'email' => 'admin_test_'.Str::random(5).'@example.com',
                'password' => Hash::make('password123'),
            ]);
        }

        return $admin;
    }

    public function test_guest_cannot_access_admin_user_crud(): void
    {
        $response = $this->get('/admin/users');
        $response->assertRedirect('/');
    }

    public function test_admin_can_view_user_list(): void
    {
        $admin = $this->getOrCreateAdmin();

        $response = $this->actingAs($admin, 'admin')->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('User Management');
    }

    public function test_admin_can_search_and_filter_users(): void
    {
        $admin = $this->getOrCreateAdmin();
        $email = 'searchable_'.Str::random(6).'@example.com';

        User::create([
            'first_name' => 'SearchableUniqueName',
            'last_name' => 'Investor',
            'email' => $email,
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/users?search=SearchableUniqueName&status=verified');

        $response->assertStatus(200);
        $response->assertSee('SearchableUniqueName');
    }

    public function test_admin_can_view_create_user_form(): void
    {
        $admin = $this->getOrCreateAdmin();

        $response = $this->actingAs($admin, 'admin')->get('/admin/users/create');

        $response->assertStatus(200);
        $response->assertSee('Create New User');
    }

    public function test_admin_can_create_user(): void
    {
        $admin = $this->getOrCreateAdmin();
        $email = 'new_user_'.Str::random(8).'@example.com';

        $response = $this->actingAs($admin, 'admin')->post('/admin/users', [
            'first_name' => 'Jonathan',
            'last_name' => 'Davis',
            'email' => $email,
            'password' => 'secretPassword123!',
            'phone' => '+15552345678',
            'company_name' => 'Davis Holdings LLC',
            'email_verified' => '1',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'first_name' => 'Jonathan',
            'last_name' => 'Davis',
            'company_name' => 'Davis Holdings LLC',
        ]);

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user->email_verified_at);

        $response->assertRedirect(route('admin.users.show', $user->id));
        $response->assertSessionHas('success');
    }

    public function test_admin_can_view_user_details(): void
    {
        $admin = $this->getOrCreateAdmin();
        $user = User::create([
            'first_name' => 'Showcase',
            'last_name' => 'Investor',
            'email' => 'showcase_'.Str::random(8).'@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/users/{$user->id}");

        $response->assertStatus(200);
        $response->assertSee('Showcase Investor');
    }

    public function test_admin_can_view_edit_user_form(): void
    {
        $admin = $this->getOrCreateAdmin();
        $user = User::create([
            'first_name' => 'Editable',
            'last_name' => 'User',
            'email' => 'editable_'.Str::random(8).'@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/users/{$user->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Edit User: Editable User');
    }

    public function test_admin_can_update_user(): void
    {
        $admin = $this->getOrCreateAdmin();
        $user = User::create([
            'first_name' => 'InitialName',
            'last_name' => 'InitialLast',
            'email' => 'update_test_'.Str::random(8).'@example.com',
            'password' => Hash::make('password123'),
            'company_name' => 'Initial Co',
        ]);

        $response = $this->actingAs($admin, 'admin')->put("/admin/users/{$user->id}", [
            'first_name' => 'UpdatedFirst',
            'last_name' => 'UpdatedLast',
            'email' => $user->email,
            'company_name' => 'Updated Ventures Corp',
            'email_verified' => '1',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'UpdatedFirst',
            'last_name' => 'UpdatedLast',
            'company_name' => 'Updated Ventures Corp',
        ]);

        $response->assertRedirect(route('admin.users.show', $user->id));
        $response->assertSessionHas('success');
    }

    public function test_admin_can_delete_user(): void
    {
        $admin = $this->getOrCreateAdmin();
        $user = User::create([
            'first_name' => 'ToDelete',
            'last_name' => 'User',
            'email' => 'delete_test_'.Str::random(8).'@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($admin, 'admin')->delete("/admin/users/{$user->id}");

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');
    }
}
