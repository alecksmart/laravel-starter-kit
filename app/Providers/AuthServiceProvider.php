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
        'App\Model'   => 'App\Policies\ModelPolicy',
        'App\User'    => 'App\Policies\UserPolicy',
        'App\Post'    => 'App\Policies\PostPolicy',
        'App\Comment' => 'App\Policies\CommentPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /**
         * --------------------------------------
         *   Users permissions
         * --------------------------------------
        */

        // Admins can manage users list
        Gate::define('manage-users-list', function ($user) {
            return $user->role === 'admin';
        });
        // Admins can create users
        Gate::define('manage-users-create', function ($user) {
            return $user->role === 'admin';
        });
        // Admins can update users
        Gate::define('manage-users-update', function ($user) {
            return $user->role === 'admin';
        });
        // Admins can manage delete users
        Gate::define('manage-users-delete', function ($user) {
            return $user->role === 'admin';
        });
    }
}
