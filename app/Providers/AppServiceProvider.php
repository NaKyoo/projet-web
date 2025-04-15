<?php

namespace App\Providers;

use App\Models\Cohort;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Partager avec toutes les vues la variable sidebarCohorts
        View::composer('*', function ($view) {
            $sidebarCohorts = collect();

            // Vérifier s'il y a un utilisateur authentifié
            if (auth()->check()) {
                $user = auth()->user();

                // On vérifie que la relation school() existe et retourne un résultat
                $userSchool = $user->school();

                // Si l'utilisateur est connecté et qu'il a une école associée
                if ($userSchool) {
                    // Si l'utilisateur est teacher selon son rôle dans users_schools
                    if ($userSchool->pivot->role === 'teacher') {
                        // Récupérer les cohortes auxquelles l'enseignant est associé via la table pivot cohort_teacher
                        $sidebarCohorts = $user->cohorts()->with('school')->orderBy('name')->get();
                    } else {
                        // Sinon (par exemple admin), afficher toutes les cohortes
                        $sidebarCohorts = Cohort::with('school')->orderBy('name')->get();
                    }
                }
            }

            $view->with('sidebarCohorts', $sidebarCohorts);
        });


    }
}
