<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create approval_tokens table for secure, single-use client access.
     * 
     * Security model:
     * - Cryptographically secure random tokens (64 chars)
     * - Single-use only (tracked via used_at)
     * - Time-limited expiry
     * - No client login required
     */
    public function up(): void
    {
        Schema::create('approval_tokens', function (Blueprint $table) {
            $table->id();
            
            // Parent approval request
            $table->foreignId('approval_request_id')
                  ->constrained('approval_requests')
                  ->onDelete('cascade');
            
            // Security token (64-char random hash)
            $table->string('token', 64)->unique();
            
            // Token lifecycle
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            
            // Usage tracking (audit trail)
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance and security
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_tokens');
    }
};
