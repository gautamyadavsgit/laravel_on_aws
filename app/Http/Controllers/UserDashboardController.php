<?php

namespace App\Http\Controllers;

use App\Models\PropertyInterest;
use App\Models\PropertyFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    public function dashboard(): View
    {
        $user = Auth::user();

        $interests = PropertyInterest::with('property.propertyAddress', 'property.propertyDetails')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $favorites = PropertyFavorite::with('property.propertyAddress', 'property.propertyDetails')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('frontend.user_dashboard', compact('user', 'interests', 'favorites'));
    }

    public function profile(): View
    {
        $user = Auth::user();

        return view('frontend.user_profile', compact('user'));
    }
}
