<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class InstallerController extends Controller
{
    /**
     * Display welcome screen
     */
    public function welcome()
    {
        // Check if already installed
        if ($this->isInstalled()) {
            return redirect('/login')->with('info', 'Application already installed.');
        }

        // Mark step as accessible
        Session::put('installer_step', 0);

        return view('installer.welcome');
    }

    /**
     * Check server requirements
     */
    public function requirements()
    {
        // Check if already installed
        if ($this->isInstalled()) {
            return redirect('/login')->with('info', 'Application already installed.');
        }

        $requirements = [
            'php' => [
                'name' => 'PHP Version 8.2+',
                'status' => version_compare(PHP_VERSION, '8.2.0', '>='),
                'required' => true
            ],
            'pdo' => [
                'name' => 'PDO PHP Extension',
                'status' => extension_loaded('pdo'),
                'required' => true
            ],
            'mbstring' => [
                'name' => 'Mbstring PHP Extension',
                'status' => extension_loaded('mbstring'),
                'required' => true
            ],
            'openssl' => [
                'name' => 'OpenSSL PHP Extension',
                'status' => extension_loaded('openssl'),
                'required' => true
            ],
            'tokenizer' => [
                'name' => 'Tokenizer PHP Extension',
                'status' => extension_loaded('tokenizer'),
                'required' => true
            ],
            'xml' => [
                'name' => 'XML PHP Extension',
                'status' => extension_loaded('xml'),
                'required' => true
            ],
            'ctype' => [
                'name' => 'Ctype PHP Extension',
                'status' => extension_loaded('ctype'),
                'required' => true
            ],
            'json' => [
                'name' => 'JSON PHP Extension',
                'status' => extension_loaded('json'),
                'required' => true
            ],
            'bcmath' => [
                'name' => 'BCMath PHP Extension',
                'status' => extension_loaded('bcmath'),
                'required' => true
            ],
            'fileinfo' => [
                'name' => 'Fileinfo PHP Extension',
                'status' => extension_loaded('fileinfo'),
                'required' => true
            ],
            'gd' => [
                'name' => 'GD PHP Extension',
                'status' => extension_loaded('gd'),
                'required' => false
            ]
        ];

        $permissions = [
            'storage/framework/' => is_writable(storage_path('framework')),
            'storage/logs/' => is_writable(storage_path('logs')),
            'bootstrap/cache/' => is_writable(base_path('bootstrap/cache')),
            '.env' => File::exists(base_path('.env'))
        ];

        $allPassed = collect($requirements)->where('required', true)->every('status', true) &&
                     collect($permissions)->every(fn($val) => $val === true);

        // Mark step as completed if all requirements passed
        if ($allPassed && Session::get('installer_step', 0) < 1) {
            Session::put('installer_step', 1);
        }

        return view('installer.requirements', compact('requirements', 'permissions', 'allPassed'));
    }

    /**
     * Show database configuration form
     */
    public function database()
    {
        // Check if already installed
        if ($this->isInstalled()) {
            return redirect('/login')->with('info', 'Application already installed.');
        }

        return view('installer.database');
    }

    /**
     * Test database connection and save configuration
     */
    public function databaseStore(Request $request)
    {
        $request->validate([
            'db_host' => 'required|string',
            'db_port' => 'required|numeric',
            'db_name' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
        ]);

        // Test database connection
        try {
            $connection = @new \PDO(
                "mysql:host={$request->db_host};port={$request->db_port};dbname={$request->db_name}",
                $request->db_username,
                $request->db_password,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_TIMEOUT => 5
                ]
            );
        } catch (\PDOException $e) {
            return back()->withErrors(['database' => 'Database connection failed: ' . $e->getMessage()])->withInput();
        }

        // Update .env file
        $this->updateEnvFile([
            'DB_HOST' => $request->db_host,
            'DB_PORT' => $request->db_port,
            'DB_DATABASE' => $request->db_name,
            'DB_USERNAME' => $request->db_username,
            'DB_PASSWORD' => $request->db_password,
        ]);

        // Mark step as completed
        Session::put('installer_step', 2);

        return redirect()->route('installer.migrate');
    }

    /**
     * Show migration page
     */
    public function migrate()
    {
        // Check if already installed
        if ($this->isInstalled()) {
            return redirect('/login')->with('info', 'Application already installed.');
        }

        return view('installer.migrate');
    }

    /**
     * Run migrations and seed database
     */
    public function migrateProcess(Request $request)
    {
        try {
            // Clear config cache to pick up new DB settings
            Artisan::call('config:clear');

            // Run migrations
            Artisan::call('migrate', ['--force' => true]);

            // CRITICAL: Always seed roles and permissions (idempotent - safe to re-run)
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\RoleAndPermissionSeeder',
                '--force' => true
            ]);

            // CRITICAL: Always seed email templates (required for system operation)
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\EmailTemplateSeeder',
                '--force' => true
            ]);

            // Seed sample data if requested (clients, approvals, etc.)
            $sampleDataSeeded = false;
            if ($request->has('seed_sample_data') && $request->seed_sample_data == '1') {
                Artisan::call('db:seed', ['--class' => 'DatabaseSeeder', '--force' => true]);
                $sampleDataSeeded = true;
            }

            // Store sample data flag in session for complete page
            Session::put('sample_data_seeded', $sampleDataSeeded);

            // Mark step as completed
            Session::put('installer_step', 3);

            return response()->json([
                'success' => true,
                'message' => 'Database migrated successfully!',
                'redirect' => route('installer.admin')
            ]);
        } catch (\Exception $e) {
            // Log the full error for debugging
            \Log::error('Installation migration failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Migration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show admin account creation form
     */
    public function admin()
    {
        // Check if already installed
        if ($this->isInstalled()) {
            return redirect('/login')->with('info', 'Application already installed.');
        }

        return view('installer.admin');
    }

    /**
     * Create admin account
     */
    public function adminStore(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            // CRITICAL: Ensure roles exist before creating admin
            $adminRole = \App\Models\Role::where('name', 'admin')->first();
            
            if (!$adminRole) {
                // If roles don't exist, seed them first
                \Artisan::call('db:seed', [
                    '--class' => 'Database\\Seeders\\RoleAndPermissionSeeder',
                    '--force' => true
                ]);
                $adminRole = \App\Models\Role::where('name', 'admin')->firstOrFail();
            }

            // Check if admin already exists (idempotency)
            $existingAdmin = User::where('email', $request->email)->first();
            
            if ($existingAdmin) {
                // Update existing admin
                $existingAdmin->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'password' => Hash::make($request->password),
                    'role_id' => $adminRole->id,
                    'status' => 'active',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);
                $user = $existingAdmin;
            } else {
                // Create new admin user
                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role_id' => $adminRole->id,
                    'status' => 'active',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);
            }

            // Verify role assignment succeeded
            if (!$user->role_id || $user->role->name !== 'admin') {
                throw new \Exception('Failed to assign Administrator role to user');
            }

            // Mark step as completed
            Session::put('installer_step', 4);

            return redirect()->route('installer.complete');
        } catch (\Exception $e) {
            \Log::error('Admin creation failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create admin account: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show installation complete page
     */
    public function complete()
    {
        // Mark installation as complete
        $this->markAsInstalled();

        // Mark final step as completed
        Session::put('installer_step', 5);

        // Get sample data flag
        $sampleDataSeeded = Session::get('sample_data_seeded', false);

        // Clear installer session after getting the flag
        Session::forget('installer_step');
        Session::forget('sample_data_seeded');

        return view('installer.complete', compact('sampleDataSeeded'));
    }

    /**
     * Update .env file
     */
    private function updateEnvFile(array $data)
    {
        $envPath = base_path('.env');
        
        if (!File::exists($envPath)) {
            File::copy(base_path('.env.example'), $envPath);
        }

        $envContent = File::get($envPath);

        foreach ($data as $key => $value) {
            // Quote DB_PASSWORD to handle special characters
            if ($key === 'DB_PASSWORD' && !empty($value)) {
                $value = '"' . addslashes($value) . '"';
            } else {
                $value = addslashes($value);
            }
            
            if (preg_match("/^{$key}=/m", $envContent)) {
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}={$value}",
                    $envContent
                );
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }

        File::put($envPath, $envContent);
    }

    /**
     * Check if application is installed
     */
    private function isInstalled(): bool
    {
        return File::exists(storage_path('installed'));
    }

    /**
     * Mark application as installed
     */
    private function markAsInstalled(): void
    {
        File::put(storage_path('installed'), now()->toString());
    }
}
