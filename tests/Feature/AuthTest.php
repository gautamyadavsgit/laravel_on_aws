<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_forgot_password_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::first();
        if (! $user) {
            $user = User::create([
                'first_name' => 'Test',
                'last_name' => 'Investor',
                'email' => 'test_investor_auth@example.com',
                'password' => bcrypt('password123'),
            ]);
        }

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('properties'));
    }

    public function test_after_login_users_are_redirected_to_the_investment_interest_page_when_requested(): void
    {
        $user = User::first();
        if (! $user) {
            $user = User::create([
                'first_name' => 'Test',
                'last_name' => 'Investor',
                'email' => 'test_investor_interest@example.com',
                'password' => bcrypt('password123'),
            ]);
        }

        $response = $this->post('/login?redirect=investment_interest', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('investment.interest'));
    }
}
