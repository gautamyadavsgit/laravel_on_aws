<?php

namespace Tests\Feature;

use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Str;
use Tests\DatabaseTestCase;

class InvestorRegistrationTest extends DatabaseTestCase
{
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

        $this->post('/register', $data)->assertRedirect(route('properties.index'));

        $this->assertDatabaseHas('users', ['email' => $data['email'], 'first_name' => 'Jane']);
        $this->assertAuthenticated();
    }

    public function test_registration_rejects_invalid_input(): void
    {
        // Route throttling has its own test; this one submits many payloads back to back.
        $this->withoutMiddleware(ThrottleRequests::class);

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
        $this->post('/register', $ok)->assertRedirect(route('properties.index'));
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
}
