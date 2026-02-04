<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Check if user has specific role.
 * 
 * Usage in routes:
 * Route::middleware(['auth', 'role:admin'])->group(...)
 * Route::middleware(['auth', 'role:admin,manager'])->group(...) // Multiple roles
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $hasRole = false;
        foreach ($roles as $role) {
            if ($request->user()->hasRole($role)) {
                $hasRole = true;
                break;
            }
        }

        if (!$hasRole) {
            abort(403, 'Unauthorized access. Required role: ' . implode(' or ', $roles));
        }

        return $next($request);
    }
}
