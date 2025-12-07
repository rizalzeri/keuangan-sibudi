<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Proker;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('bumdes', function (User $user) {
            return $user->role === 'bumdes';
        });

        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('referral', function (User $user) {
            return $user->referral == false;
        });
    }
}
