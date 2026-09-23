<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the primary administration dashboard.
     */
    public function __invoke(): View
    {
        return view('admin.dashboard', ['propertyCount' => Property::count()]);
    }
}
