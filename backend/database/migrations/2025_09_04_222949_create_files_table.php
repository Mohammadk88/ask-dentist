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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('uploader_id')->constrained('users')->onDelete('cascade');
            $table->string('path'); // File path within storage
            $table->string('disk', 50)->default('public'); // Storage disk
            $table->string('mime_type');
            $table->bigInteger('size'); // File size in bytes
            $table->timestamp('signed_expires_at')->nullable(); // For temporary signed URLs
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('uploader_id');
            $table->index('mime_type');
            $table->index('signed_expires_at');
            $table->index(['disk', 'path']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
