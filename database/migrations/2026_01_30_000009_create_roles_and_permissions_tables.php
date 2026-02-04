<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create roles and permissions system tables.
     * 
     * Implements a flexible RBAC (Role-Based Access Control) system:
     * - Roles: Define user roles (Admin, Manager, User, etc.)
     * - Permissions: Granular access control
     * - Role-Permission: Many-to-many relationship
     * - User-Role: Assigned via users table
     */
    public function up(): void
    {
        // Roles Table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'admin', 'manager', 'user'
            $table->string('display_name'); // e.g., 'Administrator'
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false); // Prevent deletion of core roles
            $table->timestamps();
        });

        // Permissions Table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'clients.create'
            $table->string('display_name'); // e.g., 'Create Clients'
            $table->string('module'); // e.g., 'clients', 'approvals', 'users'
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('module');
        });

        // Role-Permission Pivot Table
        Schema::create('permission_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['role_id', 'permission_id']);
        });

        // Add role_id to users table if not exists
        // This will be added AFTER enhance_users_table migration runs
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
