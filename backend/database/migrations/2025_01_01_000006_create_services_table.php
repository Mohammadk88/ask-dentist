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
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('category', [
                'general',
                'preventive',
                'restorative',
                'cosmetic',
                'orthodontic',
                'surgical',
                'endodontic',
                'periodontal',
                'pediatric',
                'emergency'
            ]);
            $table->integer('duration_minutes'); // Estimated duration
            $table->boolean('requires_anesthesia')->default(false);
            $table->boolean('requires_followup')->default(false);
            $table->boolean('is_emergency')->default(false);
            $table->jsonb('prerequisites')->nullable(); // Required examinations, tests
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('slug');
            $table->index('category');
            $table->index(['category', 'is_emergency']);
            $table->index('duration_minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
