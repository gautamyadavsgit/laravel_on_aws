<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Show the admin login page or redirect to dashboard.
     */
    public function index(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect(route('admin.dashboard'));
        }

        return view('admin.login');
    }

    /**
     * Handle admin login authentication.
     */
    public function login(AdminLoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect(route('admin.dashboard'));
        }

        return back()->with('error', 'The provided credentials do not match our records.')->withInput();
    }

    /**
     * Log the admin out of the session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin');
    }
}
