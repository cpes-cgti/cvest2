<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('level4', function ($user) {
            return $user->profile > 3 ;
        });

        Gate::define('level3', function ($user) {
            return $user->profile > 2 ;
        });

        Gate::define('level2', function ($user) {
            return $user->profile > 1 ;
        });

        Gate::define('level1', function ($user) {
            return $user->profile > 0 ;
        });

    }
}
