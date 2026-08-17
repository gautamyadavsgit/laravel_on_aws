<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordResetUpdateRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', [
            'token' => (string) $request->route('token'),
            'email' => (string) $request->query('email', ''),
        ]);
    }

    /**
     * Handle an incoming new password request.
     */
    public function store(PasswordResetUpdateRequest $request): RedirectResponse
    {
        $status = $this->authService->resetPassword(
            $request->validated()
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with(
                'success',
                'Your password has been updated successfully! Please sign in with your new credentials.'
            );
        }

        return back()->withErrors(['email' => __($status)])->withInput($request->only('email'));
    }
}
