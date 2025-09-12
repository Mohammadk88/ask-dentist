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
        Schema::create('profiles_doctor', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('clinic_id')->nullable()->constrained('clinics')->onDelete('set null');
            $table->string('specialty'); // e.g., 'General Dentistry', 'Orthodontics', 'Oral Surgery'
            $table->text('bio')->nullable();
            $table->json('licenses_json')->nullable(); // Professional licenses, certifications
            $table->decimal('rating', 3, 2)->default(0.00); // 0.00 to 5.00
            $table->integer('response_time_sec')->default(3600); // Average response time in seconds
            $table->integer('active_patients_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->unique('user_id');
            $table->index('clinic_id');
            $table->index('specialty');
            $table->index('rating');
            $table->index('response_time_sec');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles_doctor');
    }
};
