<?php

namespace App\Providers;

use App\Models\Vessel;
use App\Models\Property;
use App\Models\Block;
use App\Models\User;
use App\Observers\VesselObserver;
use App\Observers\PropertyObserver;
use App\Observers\BlockObserver;
use App\Observers\UserObserver;
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
        Property::observe(PropertyObserver::class);
        Block::observe(BlockObserver::class);
        User::observe(UserObserver::class);
    }
}
