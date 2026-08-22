<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\AccreditationStatus;
use App\Models\ExperienceLevel;
use App\Models\HearAboutUs;
use App\Models\InvestmentGoal;
use App\Models\InvestmentSource;
use App\Models\InvestmentTimeline;
use App\Models\InvestmentTimelength;
use App\Models\ReasonForInvesting;
use App\Models\User;
use App\Models\UserNetWorth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users with search and filter capabilities.
     */
    public function index(Request $request): View
    {
        $query = User::with([
            'propertyInterests.property.propertyAddress',
            'propertyInterests.property.propertyDetails',
            'accreditationStatus',
            'experienceLevel',
        ]);

        // Search by keyword (name, email, phone)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        // Filter by verification status
        if ($status = $request->input('status')) {
            if ($status === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($status === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        // Filter by accreditation status
        if ($accreditation = $request->input('accreditation')) {
            $query->where('accreditation_status', $accreditation);
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $accreditationStatuses = AccreditationStatus::all();

        return view('admin.users.index', compact('users', 'accreditationStatuses'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        $lookups = $this->getLookups();

        return view('admin.users.create', $lookups);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        if (!empty($data['email_verified'])) {
            $data['email_verified_at'] = now();
        } else {
            $data['email_verified_at'] = null;
        }
        unset($data['email_verified']);

        $user = User::create($data);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', "User '{$user->name}' created successfully.");
    }

    /**
     * Display the specified user profile and associated records.
     */
    public function show(User $user): View
    {
        $user->load([
            'propertyInterests.property.propertyAddress',
            'propertyInterests.property.propertyDetails',
            'propertyFavorites.property.propertyAddress',
            'propertyFavorites.property.propertyDetails',
            'searchLogs' => function ($q) {
                $q->latest()->take(10);
            },
            'accreditationStatus',
            'experienceLevel',
            'investingReason',
            'investmentSource',
            'investmentTimeline',
            'investmentGoal',
            'investmentTimelength',
            'userNetWorth',
            'hearAboutUs',
        ]);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $lookups = $this->getLookups();
        $lookups['user'] = $user;

        return view('admin.users.edit', $lookups);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        // Handle password update
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Handle email verification toggle
        if (isset($data['email_verified'])) {
            if ($data['email_verified'] && !$user->email_verified_at) {
                $data['email_verified_at'] = now();
            } elseif (!$data['email_verified']) {
                $data['email_verified_at'] = null;
            }
            unset($data['email_verified']);
        }

        $user->update($data);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', "User '{$user->name}' updated successfully.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $name = $user->name ?: $user->email;

        // Clean up user's related activity if needed
        $user->propertyInterests()->delete();
        $user->propertyFavorites()->delete();
        $user->searchLogs()->delete();

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$name}' has been deleted.");
    }

    /**
     * Helper to retrieve all lookup table options.
     */
    protected function getLookups(): array
    {
        return [
            'accreditationStatuses' => AccreditationStatus::all(),
            'experienceLevels' => ExperienceLevel::all(),
            'investingReasons' => ReasonForInvesting::all(),
            'investmentSources' => InvestmentSource::all(),
            'investmentTimelines' => InvestmentTimeline::all(),
            'investmentGoals' => InvestmentGoal::all(),
            'investmentTimelengths' => InvestmentTimelength::all(),
            'userNetWorths' => UserNetWorth::all(),
            'hearAboutOptions' => HearAboutUs::all(),
        ];
    }
}
