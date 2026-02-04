<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Client;
use App\Models\Role;
use App\Models\ApprovalRequest;
use App\Observers\UserObserver;
use App\Observers\ClientObserver;
use App\Observers\RoleObserver;
use App\Observers\ApprovalRequestObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        // Register Model Observers for automatic audit logging
        User::observe(UserObserver::class);
        Client::observe(ClientObserver::class);
        Role::observe(RoleObserver::class);
        ApprovalRequest::observe(ApprovalRequestObserver::class);
        
        // Register Auth Event Listeners
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Login::class,
            \App\Listeners\LogSuccessfulLogin::class
        );
        
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Logout::class,
            \App\Listeners\LogSuccessfulLogout::class
        );
    }
}
