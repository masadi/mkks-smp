<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        /**
         * Defining the user Roles
         */
        Gate::define('isAdmin', function ($user) {
            return $user->type === 'admin';
        });

        Gate::define('isUser', function ($user) {
            return $user->type === 'user';
        });
        Gate::define('isSekolah', function ($user) {
            return $user->type === 'sekolah';
        });
        Gate::define('isDinas', function ($user) {
            return $user->type === 'dinas';
        });
        Gate::define('isPengawas', function ($user) {
            return $user->type === 'pengawas';
        });
        Gate::define('isBendahara', function ($user) {
            return $user->type === 'bendahara';
        });
        Gate::define('isKorwil', function ($user) {
            return $user->type === 'korwil';
        });
    }
}
