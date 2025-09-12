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
        Schema::create('teeth_reference', function (Blueprint $table) {
            $table->id();
            $table->string('fdi_code', 2)->unique(); // FDI two-digit notation
            $table->string('name');
            $table->enum('type', ['incisor', 'canine', 'premolar', 'molar']);
            $table->enum('quadrant', ['upper_right', 'upper_left', 'lower_left', 'lower_right']);
            $table->integer('position_in_quadrant'); // 1-8
            $table->boolean('is_permanent')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('fdi_code');
            $table->index('type');
            $table->index('quadrant');
            $table->index(['quadrant', 'position_in_quadrant']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teeth_reference');
    }
};
