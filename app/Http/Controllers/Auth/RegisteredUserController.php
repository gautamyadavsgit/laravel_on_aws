<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\InvestorRegisterRequest;
use App\Services\RegistrationService;
use Illuminate\Database\UniqueConstraintViolationException;
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
            return redirect()->route(request('redirect') === 'investment_interest' ? 'investment.interest' : 'properties');
        }

        return view('register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(InvestorRegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $user = $this->registrationService->register($validated);
        } catch (UniqueConstraintViolationException) {
            // Lost a race against another registration using the same email
            return back()->withErrors(['email' => 'The email has already been taken.'])->withInput($request->except('password', 'password_confirmation'));
        }

        $request->session()->regenerate();

        $redirectTo = $request->input('redirect') === 'investment_interest' ? 'investment.interest' : 'properties';

        return redirect()->route($redirectTo)->with(
            'success',
            'Registration successful! A verification email with an activation link has been sent to '.$user->email.'.'
        );
    }
}
