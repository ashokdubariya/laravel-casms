<?php

namespace App\Listeners;

use App\Models\AuditLog;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogout
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
    public function handle(Logout $event): void
    {
        if ($event->user) {
            // Prevent duplicate logs in the same request
            // Check if this user's logout was already logged in the last 2 seconds
            $recentLog = AuditLog::where('user_id', $event->user->id)
                ->where('module', 'auth')
                ->where('action', 'logout')
                ->where('created_at', '>=', now()->subSeconds(2))
                ->first();

            if ($recentLog) {
                // Already logged this logout, skip to prevent duplicate
                return;
            }

            AuditLog::create([
                'user_id' => $event->user->id,
                'user_email' => $event->user->email,
                'user_name' => $event->user->full_name,
                'module' => 'auth',
                'action' => 'logout',
                'description' => "{$event->user->full_name} logged out",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }
}
