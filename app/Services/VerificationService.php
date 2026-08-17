<?php

namespace App\Services;

use App\Mail\VerifyEmailMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VerificationService
{
    /**
     * Verify email via token
     */
    public function verify(string $token): ?User
    {
        $user = User::where('verification_token', $token)->first();

        if (! $user) {
            return null;
        }

        $user->update([
            'email_verified_at' => now(),
            'verification_token' => null,
        ]);

        if (! Auth::guard('web')->check()) {
            Auth::guard('web')->login($user);
        }

        return $user;
    }

    /**
     * Resend verification email to user
     */
    public function resend(User $user): bool
    {
        if ($user->email_verified_at) {
            return true;
        }

        $token = Str::random(64);
        $verifyUrl = route('verification.verify.token', ['token' => $token]);

        $user->update([
            'verification_token' => $token,
            'verification_link' => $verifyUrl,
        ]);

        try {
            Mail::to($user->email)->queue(new VerifyEmailMail($user, $verifyUrl));

            return true;
        } catch (\Throwable $e) {
            Log::error('Failed resending verification email queue: '.$e->getMessage());

            return false;
        }
    }
}
