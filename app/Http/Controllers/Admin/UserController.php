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
use App\Models\InvestmentTimelength;
use App\Models\InvestmentTimeline;
use App\Models\ReasonForInvesting;
use App\Models\User;
use App\Models\UserNetWorth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of users with search and filter capabilities.
     */
    public function index(Request $request): View
    {
        $query = User::with(['accreditationStatus', 'experienceLevel'])
            ->withCount('propertyInterests');

        // Only plain strings are meaningful filters; arrays / junk are dropped (and removed from the query
        // string so the view and pagination links never see them)
        $search = $request->query('search');
        $search = is_string($search) && trim($search) !== '' ? mb_substr(trim($search), 0, 100) : null;

        $status = $request->query('status');
        $status = in_array($status, ['verified', 'unverified'], true) ? $status : null;

        $accreditation = $request->query('accreditation');
        $accreditation = is_string($accreditation) && ctype_digit($accreditation) && strlen($accreditation) <= 10 ? $accreditation : null;

        $page = $request->query('page');
        $page = is_string($page) && ctype_digit($page) && (int) $page >= 1 && strlen($page) <= 6 ? $page : null;

        $request->query->replace(array_filter(compact('search', 'status', 'accreditation', 'page'), fn ($v) => $v !== null));

        // Search by keyword (name, email, phone); LIKE wildcards typed by the admin are matched literally
        if ($search !== null) {
            $like = '%'.addcslashes($search, '%_\\').'%';
            $query->where(function ($q) use ($like) {
                $q->where('first_name', 'like', $like)
                    ->orWhere('last_name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('phone', 'like', $like)
                    ->orWhere('company_name', 'like', $like);
            });
        }

        // Filter by verification status
        if ($status === 'verified') {
            $query->whereNotNull('email_verified_at');
        } elseif ($status === 'unverified') {
            $query->whereNull('email_verified_at');
        }

        // Filter by accreditation status
        if ($accreditation !== null) {
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
        $attributes = $request->validated();

        $attributes['password'] = Hash::make($attributes['password']);

        if (! empty($attributes['email_verified'])) {
            $attributes['email_verified_at'] = now();
        } else {
            $attributes['email_verified_at'] = null;
        }
        unset($attributes['email_verified']);

        $user = User::create($attributes);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', "User '{$user->name}' created successfully.");
    }

    /**
     * Display the specified user profile and associated records.
     */
    public function show(User $user): View
    {
        $user->load([
            'propertyInterests.property',
            'propertyFavorites.property',
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
        $attributes = $request->validated();

        // Handle password update
        if (! empty($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        } else {
            unset($attributes['password']);
        }

        // Handle email verification toggle
        if (isset($attributes['email_verified'])) {
            if ($attributes['email_verified'] && ! $user->email_verified_at) {
                $attributes['email_verified_at'] = now();
            } elseif (! $attributes['email_verified']) {
                $attributes['email_verified_at'] = null;
            }
            unset($attributes['email_verified']);
        }

        $user->update($attributes);

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
