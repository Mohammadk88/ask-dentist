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
        Schema::create('medical_files', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // File information
            $table->string('original_name');
            $table->string('filename')->unique();
            $table->string('file_path');
            $table->bigInteger('file_size');
            $table->string('mime_type');
            $table->string('file_hash', 64)->nullable(); // SHA-256 hash

            // Ownership and relationships
            $table->uuid('uploaded_by');
            $table->string('related_to_type')->nullable(); // Polymorphic relationship
            $table->uuid('related_to_id')->nullable();

            // Classification and access
            $table->enum('file_category', [
                'xray', 'photo', 'document', 'report',
                'treatment_plan', 'prescription'
            ]);
            $table->enum('access_level', [
                'private', 'clinic', 'doctor', 'patient'
            ]);

            // Security and compliance
            $table->enum('virus_scan_status', [
                'pending', 'scanning', 'clean', 'infected', 'failed'
            ])->default('pending');
            $table->text('virus_scan_result')->nullable();

            // Expiry and metadata
            $table->timestamp('expiry_date')->nullable();
            $table->json('metadata')->nullable(); // Additional file metadata

            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['uploaded_by']);
            $table->index(['related_to_type', 'related_to_id']);
            $table->index(['file_category']);
            $table->index(['access_level']);
            $table->index(['virus_scan_status']);
            $table->index(['expiry_date']);
            $table->index(['created_at']);

            // Foreign key constraint
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_files');
    }
};
