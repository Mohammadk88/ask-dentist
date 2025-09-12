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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action'); // create, update, delete, view, login, logout, etc.
            $table->string('model_type')->nullable(); // Model class name
            $table->uuid('model_id')->nullable(); // Model UUID
            $table->ipAddress('ip_address');
            $table->text('user_agent');
            $table->jsonb('old_values')->nullable(); // Previous values for updates
            $table->jsonb('new_values')->nullable(); // New values for updates
            $table->jsonb('metadata')->nullable(); // Additional context
            $table->string('request_method')->nullable(); // GET, POST, PUT, DELETE
            $table->string('request_url')->nullable(); // Request URL
            $table->string('session_id')->nullable(); // Session identifier
            $table->timestamp('created_at');

            // Indexes
            $table->index('user_id');
            $table->index('action');
            $table->index(['model_type', 'model_id']);
            $table->index('created_at');
            $table->index(['user_id', 'action']);
            $table->index(['user_id', 'created_at']);
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
