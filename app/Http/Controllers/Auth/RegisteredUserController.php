<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\InvestorRegisterRequest;
use App\Services\RegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    protected RegistrationService $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    /**
     * Display the registration view.
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('properties');
        }

        return view('register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(InvestorRegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = $this->registrationService->register($validated);

        return redirect()->route('properties')->with(
            'success',
            'Registration successful! A verification email with an activation link has been sent to '.$user->email.'.'
        );
    }
}
