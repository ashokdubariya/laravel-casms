<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create clients table - central entity for approval management.
     * 
     * Clients are the end-users who receive and respond to approval requests.
     * This separates client data from system users (internal team members).
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            
            // Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            
            // Company Information
            $table->string('company_name')->nullable();
            $table->string('website')->nullable();
            
            // Address (optional)
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            
            // Status & Metadata
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('notes')->nullable(); // Internal notes about client
            
            // Tracking (no FK constraints - will be added later)
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            
            $table->timestamps();
            $table->softDeletes(); // Soft delete for data integrity
            
            // Indexes for performance
            $table->index('email');
            $table->index('status');
            $table->index('company_name');
            $table->index('created_at');
            $table->index('created_by');
            $table->index('updated_by');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
