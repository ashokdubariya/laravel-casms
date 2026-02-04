<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create approval_requests table - core entity for approval workflows.
     * 
     * CONSOLIDATED MIGRATION - All approval request fields in one place
     * Links approvals to clients and tracks full lifecycle
     */
    public function up(): void
    {
        Schema::create('approval_requests', function (Blueprint $table) {
            $table->id();
            
            // Creator & Client relationships
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->foreignId('client_id')->constrained('clients')->onDelete('restrict');
            
            // Request details
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('message')->nullable(); // Message to client
            $table->string('version', 50)->default('v1');
            
            // Priority & Scheduling
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->date('due_date')->nullable();
            
            // Legacy client fields (for backward compatibility - client data duplicated for email sending)
            $table->string('client_name')->nullable();
            $table->string('client_email')->nullable();
            
            // Status tracking
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            
            // Client feedback
            $table->text('client_comment')->nullable();
            
            // Team-only notes (never shown to clients)
            $table->text('internal_notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index('created_by');
            $table->index('client_id');
            $table->index('client_email');
            $table->index('status');
            $table->index('priority');
            $table->index('due_date');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_requests');
    }
};
