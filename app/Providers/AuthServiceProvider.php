<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

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
        // Unlog user if we are on the primary domain, not the bko subdomain
        if (request()->getHost() == config('app.domain')) {
            Auth::logout();
        } elseif ($this->app->environment() === 'local') {
            // Automatically sign in admin on local development
            //  Auth::login(User::whereEmail('admin@admin.example')->first(), true);
        }

        $this->registerPolicies();
    }
}
