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
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignUuid('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignUuid('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->foreignUuid('appointment_id')->nullable()->constrained('appointments')->onDelete('set null');
            $table->integer('rating'); // 1-5 stars
            $table->text('comment')->nullable();
            $table->jsonb('criteria_ratings')->nullable(); // Individual ratings for different aspects
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Constraints
            $table->unique(['patient_id', 'appointment_id']); // One review per appointment

            // Indexes
            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('clinic_id');
            $table->index('appointment_id');
            $table->index('rating');
            $table->index(['doctor_id', 'is_published']);
            $table->index(['clinic_id', 'is_published']);
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
