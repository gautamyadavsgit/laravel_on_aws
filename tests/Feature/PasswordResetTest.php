<?php

namespace Tests\Feature;

use Illuminate\Support\Str;
use Tests\DatabaseTestCase;

class PasswordResetTest extends DatabaseTestCase
{
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
}
