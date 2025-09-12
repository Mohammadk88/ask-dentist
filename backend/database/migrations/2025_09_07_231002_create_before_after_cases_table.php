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
        Schema::create('before_after_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignUuid('clinic_id')->nullable()->constrained('clinics')->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('before_path'); // Storage path for before image
            $table->string('after_path');  // Storage path for after image
            $table->json('tags')->nullable(); // ['teeth whitening', 'cosmetic', etc]
            $table->string('treatment_type')->nullable();
            $table->integer('duration_days')->nullable();
            $table->text('procedure_details')->nullable();
            $table->string('cost_range')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();

            // Indexes
            $table->index('doctor_id');
            $table->index('clinic_id');
            $table->index('is_featured');
            $table->index('status');
            $table->index(['status', 'is_approved']);
            $table->index(['is_featured', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('before_after_cases');
    }
};
