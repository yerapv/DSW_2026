<?php

namespace App\Providers;

use App\Ejercicios\E07\Models\Nota;
use App\Ejercicios\E07\Policies\NotaPolicy;
use Illuminate\Support\Facades\Gate;
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
        Gate::policy(Nota::class, NotaPolicy::class);
    }
}
