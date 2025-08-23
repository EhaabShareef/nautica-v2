<?php

namespace App\Providers;

use App\Models\Vessel;
use App\Observers\VesselObserver;
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
        // Register model observers
        Vessel::observe(VesselObserver::class);
    }
}
