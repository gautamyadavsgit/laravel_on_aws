<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * Attempt investor authentication with credentials
     */
    public function login(array $credentials, bool $remember = false): bool
    {
        return Auth::guard('web')->attempt($credentials, $remember);
    }

    /**
     * Sign out authenticated user and invalidate session if present
     */
    public function logout(): void
    {
        Auth::guard('web')->logout();

        if (request()->hasSession()) {
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }
    }

    /**
     * Send password reset link to user
     */
    public function sendPasswordResetLink(string $email): string
    {
        return Password::sendResetLink(['email' => $email]);
    }

    /**
     * Reset user password
     */
    public function resetPassword(array $data): string
    {
        return Password::reset(
            $data,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );
    }
}
