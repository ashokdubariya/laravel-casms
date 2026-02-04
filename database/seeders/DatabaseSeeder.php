<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with SAMPLE DATA ONLY
     * 
     * IMPORTANT: This seeder is for SAMPLE DATA only.
     * Core data (roles, permissions) should be seeded via installer.
     * 
     * However, for developer convenience (migrate:fresh --seed), 
     * we'll check if roles exist and seed them if missing.
     */
    public function run(): void
    {
        // DEVELOPER CONVENIENCE: Seed roles/permissions if they don't exist
        // This allows `php artisan migrate:fresh --seed` to work for development
        // The installer calls RoleAndPermissionSeeder separately for production
        if (\App\Models\Role::count() === 0) {
            $this->command->info('Seeding core data (roles & permissions)...');
            $this->call(RoleAndPermissionSeeder::class);
        }
        
        // Seed sample users (if UserSeeder exists)
        if (class_exists(\Database\Seeders\UserSeeder::class)) {
            $this->call(UserSeeder::class);
        }
        
        // Seed sample clients
        $this->call(ClientSeeder::class);
        
        // Seed sample approval requests (if ApprovalRequestSeeder exists)
        if (class_exists(\Database\Seeders\ApprovalRequestSeeder::class)) {
            $this->call(ApprovalRequestSeeder::class);
        }
        
        // Seed email templates
        if (class_exists(\Database\Seeders\EmailTemplateSeeder::class)) {
            $this->call(EmailTemplateSeeder::class);
        }
        
        $this->command->info('Sample data seeded successfully');
    }
}
