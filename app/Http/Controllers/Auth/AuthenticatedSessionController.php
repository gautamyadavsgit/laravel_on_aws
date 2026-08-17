<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\InvestorLoginRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Display the login view.
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('properties');
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(InvestorLoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if ($this->authService->login($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('properties'))->with(
                'success',
                'Welcome back, '.Auth::guard('web')->user()->first_name.'!'
            );
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email', 'remember'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->authService->logout();

        return redirect()->route('login')->with('success', 'You have been signed out successfully.');
    }
}
