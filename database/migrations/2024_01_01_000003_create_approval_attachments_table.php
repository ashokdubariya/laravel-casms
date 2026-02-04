<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create approval_attachments table for files, images, and URLs.
     */
    public function up(): void
    {
        Schema::create('approval_attachments', function (Blueprint $table) {
            $table->id();
            
            // Parent approval request
            $table->foreignId('approval_request_id')
                  ->constrained('approval_requests')
                  ->onDelete('cascade'); // Delete attachments when approval deleted
            
            // Attachment type
            $table->enum('type', ['image', 'document', 'url']);
            
            // File storage (for image/document types)
            $table->string('file_path', 500)->nullable();
            $table->string('file_name')->nullable();
            $table->unsignedBigInteger('file_size')->nullable(); // bytes
            $table->string('mime_type', 100)->nullable();
            
            // URL storage (for url type)
            $table->string('url', 500)->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('type');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_attachments');
    }
};
