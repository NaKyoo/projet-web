<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord selon le rôle de l'utilisateur
     * Cette méthode récupère les informations pertinentes pour l'affichage du tableau de bord
     * en fonction du rôle de l'utilisateur connecté
     *
     * @return \Illuminate\View\View
     */
    public function index() {

        // Récupère le rôle de l'utilisateur actuellement connecté dans l'école
        $userRole = auth()->user()->school()->pivot->role;

        // Récupère toutes les cohortes disponibles
        $cohorts = Cohort::all();

        // Récupère tous les utilisateurs enregistrés
        $users = User::all();

        // Filtre les utilisateurs pour obtenir les étudiants
        $students = $users->filter(function ($user) {
            // Vérifie que l'utilisateur a une école et que son rôle est 'student'
            return $user->school() && $user->school()->pivot->role === 'student';
        });

        // Filtre les utilisateurs pour obtenir les enseignants
        $teachers = $users->filter(function ($user) {
            // Vérifie que l'utilisateur a une école et que son rôle est 'teacher'
            return $user->school() && $user->school()->pivot->role === 'teacher';
        });

        // Compteur
        $teachersCount = $teachers->count();
        $studentsCount = $students->count();
        $cohortsCount = $cohorts->count();

        // Retourne la vue du tableau de bord correspondant au rôle de l'utilisateur
        return view('pages.dashboard.dashboard-' . $userRole, compact('cohorts',
            'studentsCount', 'teachersCount', 'cohortsCount'));

    }
}
