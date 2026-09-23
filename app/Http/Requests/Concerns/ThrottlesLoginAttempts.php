<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

/**
 * Shared login throttling for the investor and admin login requests.
 * Each request supplies its own throttle key so the two limiters never share a bucket.
 */
trait ThrottlesLoginAttempts
{
    private const MAX_LOGIN_ATTEMPTS = 5;

    abstract public function throttleKey(): string;

    /**
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), self::MAX_LOGIN_ATTEMPTS)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function recordFailedAttempt(): void
    {
        RateLimiter::hit($this->throttleKey());
    }

    public function clearFailedAttempts(): void
    {
        RateLimiter::clear($this->throttleKey());
    }
}
