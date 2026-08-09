<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmailMail;
use App\Models\PropertyModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index()
    {
        return view('register');
    }

    public function investor()
    {
        $properties = PropertyModel::with(['propertyImage', 'propertyAddress', 'propertyMetrics', 'propertyDetails'])
            ->latest()
            ->paginate(9)
            ->onEachSide(2)
            ->withQueryString();

        return view('frontend.properties', compact('properties'));
    }

    public function property_singlepage(Request $request)
    {
        $propertyId = $request->query('id');
        $query = PropertyModel::with([
            'propertyImage',
            'propertyFloorplan',
            'propertyDocumentModel',
            'propertyAddress',
            'propertyDetails',
            'propertyAmenities',
            'propertyMetrics'
        ]);

        $featuredProperty = $propertyId ? $query->find($propertyId) : $query->first();

        return view('frontend.property_singlepage', compact('featuredProperty'));
    }

    public function registerStore(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'phone' => ['nullable', 'string', 'max:50'],
            'referral' => ['nullable', 'string'],
            'experience' => ['nullable', 'string'],
            'goals' => ['nullable', 'array'],
        ]);

        // Map experience level to lookup ID
        $expMap = [
            'beginner' => 1,
            'intermediate' => 2,
            'advanced' => 3,
            'accredited' => 4,
        ];
        $expId = $expMap[$request->input('experience')] ?? 1;

        // Map referral to lookup ID
        $refMap = [
            'search' => 1,
            'referral' => 2,
            'social' => 4,
            'podcast' => 3,
        ];
        $refId = $refMap[$request->input('referral')] ?? 1;

        // Map primary goal to ID
        $goals = (array) $request->input('goals', []);
        $goalId = 1;
        if (in_array('appreciation', $goals)) $goalId = 2;
        elseif (in_array('tax_benefits', $goals)) $goalId = 3;
        elseif (in_array('diversification', $goals)) $goalId = 4;

        // Generate verification token and link
        $token = Str::random(64);
        $verifyUrl = route('verification.verify.token', ['token' => $token]);

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

        // DIRECT (SYNCHRONOUS) EMAIL SENDING:
        // Ready for future queue/background job by swapping send() to queue() or implementing ShouldQueue on VerifyEmailMail
        try {
            Mail::to($user->email)->send(new VerifyEmailMail($user, $verifyUrl));
        } catch (\Throwable $e) {
            Log::error('Failed sending verification email directly: ' . $e->getMessage());
        }

        Auth::login($user);

        return redirect()->route('properties')->with('success', 'Registration successful! A verification email with an activation link has been sent to ' . $user->email . '.');
    }

    public function verifyEmail($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('properties')->with('error', 'Invalid or expired email verification link.');
        }

        $user->update([
            'email_verified_at' => now(),
            'verification_token' => null,
        ]);

        if (!Auth::check()) {
            Auth::login($user);
        }

        return redirect()->route('properties')->with('success', 'Your email address (' . $user->email . ') has been verified successfully! Welcome to Gautam Real Estate.');
    }

    public function show($id)
    {
        // Logic to show a specific customer by ID
    }

    public function create()
    {
        // Logic to show a form for creating a new customer
    }

    public function store(Request $request)
    {
        // Logic to store a new customer in the database
    }

    public function edit($id)
    {
        // Logic to show a form for editing a specific customer
    }

    public function update(Request $request, $id)
    {
        // Logic to update a specific customer in the database
    }

    public function destroy($id)
    {
        // Logic to delete a specific customer from the database
    }
}

