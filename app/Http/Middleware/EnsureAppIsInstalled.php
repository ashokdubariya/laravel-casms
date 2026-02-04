<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class EnsureAppIsInstalled
{
    /**
     * Handle an incoming request.
     * 
     * Redirects to installer if app is not installed.
     * CRITICAL: Prevents access to all routes before installation completes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check during testing
        if (app()->environment('testing')) {
            return $next($request);
        }

        // Skip check for installer routes
        if ($request->is('installer/*') || $request->is('install')) {
            return $next($request);
        }

        // Skip check for approval routes (public, token-protected)
        if ($request->is('approval/*')) {
            return $next($request);
        }

        // Check if app is installed
        if (!$this->isInstalled()) {
            // If requesting root, redirect to installer
            if ($request->is('/')) {
                return redirect()->route('installer.welcome');
            }
            
            // For any other route, redirect with message
            return redirect()
                ->route('installer.welcome')
                ->with('warning', 'Please complete the installation process first.');
        }

        return $next($request);
    }

    /**
     * Check if application is installed
     */
    private function isInstalled(): bool
    {
        return File::exists(storage_path('installed'));
    }
}
