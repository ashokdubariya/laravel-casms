<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('approval_requests', function (Blueprint $table) {
            // Composite index for common queries (created_by + status)
            // Note: Individual indexes already exist in create_approval_requests_table
            $table->index(['created_by', 'status'], 'idx_approval_requests_creator_status');
        });

        Schema::table('approval_histories', function (Blueprint $table) {
            // Add index on created_at for timeline ordering
            $table->index('created_at', 'idx_approval_history_created_at');
        });

        Schema::table('approval_tokens', function (Blueprint $table) {
            // Add index on expires_at for cleanup queries
            $table->index('expires_at', 'idx_approval_tokens_expires_at');
            
            // Add index on used_at for filtering used tokens
            $table->index('used_at', 'idx_approval_tokens_used_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approval_requests', function (Blueprint $table) {
            $table->dropIndex('idx_approval_requests_creator_status');
        });

        Schema::table('approval_histories', function (Blueprint $table) {
            $table->dropIndex('idx_approval_history_created_at');
        });

        Schema::table('approval_tokens', function (Blueprint $table) {
            $table->dropIndex('idx_approval_tokens_expires_at');
            $table->dropIndex('idx_approval_tokens_used_at');
        });
    }
};
