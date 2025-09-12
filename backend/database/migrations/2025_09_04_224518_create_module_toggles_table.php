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
        Schema::create('module_toggles', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., 'reviews', 'travel', 'telemedicine'
            $table->boolean('enabled')->default(true);
            $table->text('description')->nullable(); // Human-readable description of the module
            $table->timestamps();

            // Indexes
            $table->index('enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_toggles');
    }
};
