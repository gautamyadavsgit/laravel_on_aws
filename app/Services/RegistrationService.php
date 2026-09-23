<?php

namespace App\Services;

use App\Mail\VerifyEmailMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegistrationService
{
    /**
     * Experience lookup mapping
     */
    protected array $experienceMap = [
        'beginner' => 1,
        'intermediate' => 2,
        'advanced' => 3,
        'accredited' => 4,
    ];

    /**
     * Referral source lookup mapping
     */
    protected array $referralMap = [
        'search' => 1,
        'referral' => 2,
        'social' => 4,
        'podcast' => 3,
    ];

    /**
     * Register a new investor user
     */
    public function register(array $validated): User
    {
        $expId = $this->experienceMap[$validated['experience'] ?? 'beginner'] ?? 1;
        $refId = $this->referralMap[$validated['referral'] ?? 'search'] ?? 1;

        $goals = (array) ($validated['goals'] ?? []);
        $goalId = 1;
        if (in_array('appreciation', $goals)) {
            $goalId = 2;
        } elseif (in_array('tax_benefits', $goals)) {
            $goalId = 3;
        } elseif (in_array('diversification', $goals)) {
            $goalId = 4;
        }

        $token = Str::random(64);
        $verifyUrl = route('verification.verify.token', ['token' => $token]);

        // The account and its default alert preferences are created together or not at all
        $user = DB::transaction(function () use ($validated, $token, $verifyUrl, $refId, $expId, $goalId) {
            $user = User::create([
                'first_name' => $validated['first_name'],
                'middile_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'suffix' => $validated['suffix'] ?? null,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'verification_token' => $token,
                'verification_link' => $verifyUrl,
                'hear_about_us' => $refId,
                'experiance_level' => $expId,
                'investment_goals' => $goalId,
                'email_verified_at' => null,
            ]);

            // Auto create default user alerts
            DB::table('user_alerts')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'new_deals' => 1,
                    'system_notice' => 1,
                    'emails' => 1,
                    'sms' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            return $user;
        });

        // Dispatch queued verification email asynchronously
        try {
            Mail::to($user->email)->queue(new VerifyEmailMail($user, $verifyUrl));
        } catch (\Throwable $e) {
            Log::error('Failed dispatching verification email queue: '.$e->getMessage());
        }

        // Auto log in new user
        Auth::guard('web')->login($user);

        return $user;
    }
}
