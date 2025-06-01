<?php

namespace App\Providers;

use App\Models\Semester;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        View::composer('components.layouts.app.sidebar', function ($view) {
            $activeSemester = Semester::getActiveSemester();
            $view->with('activeSemester', $activeSemester);
        });
    }
}
