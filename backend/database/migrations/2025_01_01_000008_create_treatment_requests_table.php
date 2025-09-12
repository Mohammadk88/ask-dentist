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
        Schema::create('treatment_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('patient_id')->constrained('patients')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('urgency', ['low', 'medium', 'high', 'emergency']);
            $table->jsonb('symptoms')->nullable(); // Pain level, duration, location
            $table->jsonb('affected_teeth')->nullable(); // FDI notation
            $table->jsonb('photos')->nullable(); // URLs to uploaded photos
            $table->enum('status', [
                'pending',
                'reviewing',
                'quote_requested',
                'quoted',
                'accepted',
                'scheduled',
                'in_progress',
                'completed',
                'cancelled'
            ])->default('pending');
            $table->timestamp('preferred_date')->nullable();
            $table->jsonb('preferred_times')->nullable(); // Preferred time slots
            $table->boolean('is_emergency')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('patient_id');
            $table->index('status');
            $table->index('urgency');
            $table->index(['status', 'urgency']);
            $table->index('is_emergency');
            $table->index('preferred_date');
            $table->index(['patient_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_requests');
    }
};
