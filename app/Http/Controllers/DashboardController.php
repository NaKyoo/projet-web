<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $userRole = auth()->user()->school()->pivot->role;

        $cohorts = Cohort::all();

        return view('pages.dashboard.dashboard-' . $userRole, compact('cohorts'));

    }
}
