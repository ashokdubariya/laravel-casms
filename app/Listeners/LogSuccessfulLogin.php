<?php

namespace App\Listeners;

use App\Models\AuditLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // Prevent duplicate logs in the same request
        // Check if this user's login was already logged in the last 2 seconds
        $recentLog = AuditLog::where('user_id', $event->user->id)
            ->where('module', 'auth')
            ->where('action', 'login')
            ->where('created_at', '>=', now()->subSeconds(2))
            ->first();

        if ($recentLog) {
            // Already logged this login, skip to prevent duplicate
            return;
        }

        AuditLog::create([
            'user_id' => $event->user->id,
            'user_email' => $event->user->email,
            'user_name' => $event->user->full_name,
            'module' => 'auth',
            'action' => 'login',
            'description' => "{$event->user->full_name} logged in",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
