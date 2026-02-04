<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed 3 users (1 per role) with realistic data.
     * 
     * IDEMPOTENT: Uses firstOrCreate to prevent duplicates
     */
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $userRole = Role::where('name', 'user')->first();

        // Create/update secondary admin user (idempotent)
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'James',
                'last_name' => 'Anderson',
                'name' => 'James Anderson',
                'phone' => '+1-555-0101',
                'password' => Hash::make('password123'),
                'role_id' => $adminRole?->id,
                'status' => 'active',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => now()->subHours(2),
            ]
        );

        // Create/update 1 manager user (idempotent)
        User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Mitchell',
                'name' => 'Sarah Mitchell',
                'phone' => '+1-555-0102',
                'password' => Hash::make('password123'),
                'role_id' => $managerRole?->id,
                'status' => 'active',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => now()->subHours(5),
            ]
        );

        // Create/update 1 regular user (idempotent)
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'first_name' => 'Michael',
                'last_name' => 'Roberts',
                'name' => 'Michael Roberts',
                'phone' => '+1-555-0103',
                'password' => Hash::make('password123'),
                'role_id' => $userRole?->id,
                'status' => 'active',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => now()->subDays(1),
            ]
        );

        $this->command->info('Created/updated 3 users (1 admins, 1 manager, 1 user)');
    }
}
