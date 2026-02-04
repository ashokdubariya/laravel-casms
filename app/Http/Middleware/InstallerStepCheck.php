<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class InstallerStepCheck
{
    /**
     * Handle an incoming request.
     * 
     * Enforces sequential installation steps - prevents skipping steps.
     */
    public function handle(Request $request, Closure $next, string $requiredStep): Response
    {
        // If already installed, redirect to login
        if ($this->isInstalled()) {
            return redirect('/login')->with('info', 'Application is already installed.');
        }

        // Define step order
        $steps = [
            'welcome' => 0,
            'requirements' => 1,
            'database' => 2,
            'migrate' => 3,
            'admin' => 4,
            'complete' => 5,
        ];

        $currentStepNumber = $steps[$requiredStep] ?? 0;
        $completedStep = Session::get('installer_step', 0);

        // Allow access to current step or previous steps
        if ($currentStepNumber <= $completedStep + 1) {
            return $next($request);
        }

        // Redirect to the next valid step
        $nextStep = $this->getStepRoute($completedStep + 1);
        return redirect()->route($nextStep)->with('error', 'Please complete the installation steps in order.');
    }

    /**
     * Get route name for a step number
     */
    private function getStepRoute(int $stepNumber): string
    {
        $routes = [
            0 => 'installer.welcome',
            1 => 'installer.requirements',
            2 => 'installer.database',
            3 => 'installer.migrate',
            4 => 'installer.admin',
            5 => 'installer.complete',
        ];

        return $routes[$stepNumber] ?? 'installer.welcome';
    }

    /**
     * Check if application is installed
     */
    private function isInstalled(): bool
    {
        return File::exists(storage_path('installed'));
    }
}
