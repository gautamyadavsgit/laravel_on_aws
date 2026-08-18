<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropertyInterest;
use App\Models\User;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(): View
    {
        $users = User::with(['propertyInterests.property.propertyAddress', 'propertyInterests.property.propertyDetails'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->load(['propertyInterests.property.propertyAddress', 'propertyInterests.property.propertyDetails']);

        return view('admin.users.show', compact('user'));
    }
}
