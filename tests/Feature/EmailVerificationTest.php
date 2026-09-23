<?php

namespace Tests\Feature;

use Illuminate\Support\Str;
use Tests\DatabaseTestCase;

class EmailVerificationTest extends DatabaseTestCase
{
    public function test_email_verification_handles_bad_tokens_and_is_single_use(): void
    {
        $this->get('/verify-email/'.str_repeat('a', 64))->assertRedirect(route('properties.index'))->assertSessionHas('error');

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
}
