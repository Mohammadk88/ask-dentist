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
        Schema::create('request_doctor', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('request_id')->constrained('treatment_requests')->onDelete('cascade');
            $table->foreignUuid('doctor_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('sent_at');
            $table->enum('status', ['pending', 'responded', 'declined'])->default('pending');
            $table->timestamps();

            // Indexes
            $table->unique(['request_id', 'doctor_id']);
            $table->index('doctor_id');
            $table->index('status');
            $table->index('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_doctor');
    }
};
