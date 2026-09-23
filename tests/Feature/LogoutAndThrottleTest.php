<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LogoutAndThrottleTest extends TestCase
{
    use DatabaseTransactions;

    public function test_investor_logout_requires_post(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/logout')->assertStatus(405);
        $this->assertAuthenticatedAs($user);

        $this->actingAs($user)->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_admin_logout_requires_post(): void
    {
        $admin = Admin::first() ?? Admin::create(['username' => 'logout-test', 'email' => 'logout-test@example.com', 'password' => Hash::make('password123')]);

        $this->actingAs($admin, 'admin')->get('/admin/logout')->assertStatus(405);
        $this->assertAuthenticatedAs($admin, 'admin');

        $this->actingAs($admin, 'admin')->post('/admin/logout')->assertRedirect('/admin');
        $this->assertGuest('admin');
    }

    public function test_logout_controls_are_forms_not_links(): void
    {
        $user = User::factory()->create();

        $html = $this->actingAs($user)->get('/dashboard')->assertOk()->getContent();

        $this->assertStringNotContainsString('href="'.route('logout').'"', $html);
        $this->assertStringContainsString('action="'.route('logout').'"', $html);
    }

    public function test_forgot_password_is_rate_limited(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $this->post('/forgot-password', ['email' => 'nobody@example.com']);
        }

        $this->post('/forgot-password', ['email' => 'nobody@example.com'])->assertStatus(429);
    }
}
