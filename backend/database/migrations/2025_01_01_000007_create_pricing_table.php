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
        Schema::create('pricing', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->foreignUuid('service_id')->constrained('services')->onDelete('cascade');
            $table->decimal('base_price', 10, 2);
            $table->string('currency', 3); // ISO 4217 currency code
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->timestamp('valid_from');
            $table->timestamp('valid_until')->nullable();
            $table->jsonb('conditions')->nullable(); // Special conditions, requirements
            $table->boolean('is_negotiable')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Constraints
            $table->unique(['clinic_id', 'service_id', 'valid_from']);

            // Indexes
            $table->index('clinic_id');
            $table->index('service_id');
            $table->index(['clinic_id', 'service_id']);
            $table->index(['valid_from', 'valid_until']);
            $table->index('currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing');
    }
};
