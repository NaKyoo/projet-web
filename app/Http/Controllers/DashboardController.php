<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $userRole = auth()->user()->school()->pivot->role;

        $cohorts = Cohort::all();

        // Récupérer tous les utilisateurs
        $users = User::all();

        // Filtrer les étudiants
        $students = $users->filter(function ($user) {
            return $user->school() && $user->school()->pivot->role === 'student';
        });

        // Filtrer les enseignants
        $teachers = $users->filter(function ($user) {
            return $user->school() && $user->school()->pivot->role === 'teacher';
        });

        // Compter le nombre d'enseignants
        $teachersCount = $teachers->count();
        // Compter le nombre d'étudiants
        $studentsCount = $students->count();
        // Compter le nombre de promotions
        $cohortsCount = $cohorts->count();

        return view('pages.dashboard.dashboard-' . $userRole, compact('cohorts',
            'studentsCount', 'teachersCount', 'cohortsCount'));

    }
}
