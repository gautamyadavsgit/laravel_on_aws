<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordResetEmailRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     */
    public function store(PasswordResetEmailRequest $request): RedirectResponse
    {
        $status = $this->authService->sendPasswordResetLink($request->validated('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'A password reset link has been dispatched to your email address.');
        }

        return back()->withErrors(['email' => __($status)])->withInput($request->only('email'));
    }
}
