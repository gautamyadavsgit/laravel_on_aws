<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\VerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerificationNotificationController extends Controller
{
    protected VerificationService $verificationService;

    public function __construct(VerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Please log in to resend your verification email.');
        }

        if ($user->email_verified_at) {
            return back()->with('success', 'Your email address is already verified.');
        }

        $sent = $this->verificationService->resend($user);

        if ($sent) {
            return back()->with('success', 'A new verification email has been dispatched to '.$user->email.'.');
        }

        return back()->with('error', 'Unable to send verification email right now. Please try again.');
    }
}
