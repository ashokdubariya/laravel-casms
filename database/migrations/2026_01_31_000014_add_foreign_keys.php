<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add foreign keys after all tables are created.
     * 
     * This migration adds foreign key constraints that reference tables
     * created in later migrations (solving circular dependency issues).
     */
    public function up(): void
    {
        // Add foreign keys to users table
        Schema::table('users', function (Blueprint $table) {
            // Add role_id foreign key (references roles table created later)
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            
            // Add audit tracking foreign keys (self-referencing)
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
        
        // Add foreign keys to clients table
        Schema::table('clients', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });
        
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });
    }
};
