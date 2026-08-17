<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\VerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    protected VerificationService $verificationService;

    public function __construct(VerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    /**
     * Mark the authenticated user's email address as verified using token.
     */
    public function __invoke(Request $request, string $token): RedirectResponse
    {
        $user = $this->verificationService->verify($token);

        if (! $user) {
            return redirect()->route('properties')->with(
                'error',
                'Invalid or expired email verification link.'
            );
        }

        return redirect()->route('properties')->with(
            'success',
            'Your email address ('.$user->email.') has been verified successfully! Welcome to Gautam Real Estate.'
        );
    }
}
