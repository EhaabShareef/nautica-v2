<?php

namespace App\Providers;

use App\Models\AppType;
use App\Models\Setting;
use App\Models\Vessel;
use App\Policies\AppTypePolicy;
use App\Policies\SettingPolicy;
use App\Policies\VesselPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Setting::class => SettingPolicy::class,
        AppType::class => AppTypePolicy::class,
        Vessel::class => VesselPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
