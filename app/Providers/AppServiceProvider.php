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

        // On passe les données de la school à toutes les vues
        View::composer('*', function ($view) {
            $view->with('sidebarCohorts', Cohort::with('school')->orderBy('name')->get());
        });


    }
}
