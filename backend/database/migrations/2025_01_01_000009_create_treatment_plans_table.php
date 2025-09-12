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
        Schema::create('treatment_plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('treatment_request_id')->constrained('treatment_requests')->onDelete('cascade');
            $table->foreignUuid('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignUuid('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->text('diagnosis');
            $table->jsonb('services')->nullable(); // Array of service IDs with quantities
            $table->decimal('total_cost', 10, 2);
            $table->string('currency', 3);
            $table->integer('estimated_duration_days');
            $table->integer('number_of_visits');
            $table->jsonb('timeline')->nullable(); // Detailed treatment timeline
            $table->text('pre_treatment_instructions')->nullable();
            $table->text('post_treatment_instructions')->nullable();
            $table->jsonb('risks_and_complications')->nullable();
            $table->jsonb('alternatives')->nullable(); // Alternative treatment options
            $table->enum('status', [
                'draft',
                'submitted',
                'accepted',
                'rejected',
                'expired'
            ])->default('draft');
            $table->timestamp('expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('treatment_request_id');
            $table->index('doctor_id');
            $table->index('clinic_id');
            $table->index('status');
            $table->index(['doctor_id', 'status']);
            $table->index(['clinic_id', 'status']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_plans');
    }
};
