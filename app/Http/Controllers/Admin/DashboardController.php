<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the primary administration dashboard.
     */
    public function viewDashboard(): View
    {
        return view('admin.dashboard');
    }
}
