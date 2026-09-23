<?php

namespace Tests\Feature;

use Tests\DatabaseTestCase;

class InvestorLoginTest extends DatabaseTestCase
{
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
        ])->assertRedirect(route('properties.index'));
    }
}
