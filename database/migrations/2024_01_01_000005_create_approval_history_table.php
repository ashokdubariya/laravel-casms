<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create approval_history table for immutable audit trail.
     * 
     * Design principles:
     * - Immutable: No updates after creation
     * - Comprehensive: Logs all approval lifecycle events
     * - Traceable: IP, user agent, performer tracking
     */
    public function up(): void
    {
        Schema::create('approval_histories', function (Blueprint $table) {
            $table->id();
            
            // Parent approval request
            $table->foreignId('approval_request_id')
                  ->constrained('approval_requests')
                  ->onDelete('cascade');
            
            // Action tracking
            $table->string('action', 100); // e.g., 'created', 'approved', 'rejected', 'reminded'
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // User who performed action
            $table->string('performed_by')->nullable(); // e.g., 'client', 'system', user name
            $table->string('version', 50)->nullable(); // Version at time of action
            
            // Additional context
            $table->text('comment')->nullable(); // Rejection reason, notes
            
            // Audit metadata
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable(); // Flexible field for additional data
            
            // Immutability: only created_at, no updated_at
            $table->timestamp('created_at')->nullable();
            
            // Indexes
            $table->index('action');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_histories');
    }
};
