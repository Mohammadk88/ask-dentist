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
        Schema::create('treatment_request_doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('treatment_request_id')->constrained('treatment_requests')->onDelete('cascade');
            $table->foreignUuid('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->integer('dispatch_order')->comment('Order in which doctor was selected (1-5)');
            $table->float('dispatch_score', 8, 4)->comment('Score used for selection');
            $table->enum('status', ['pending', 'accepted', 'declined', 'expired'])
                  ->default('pending');
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->text('decline_reason')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['treatment_request_id', 'dispatch_order']);
            $table->index(['doctor_id', 'status']);
            $table->index(['status', 'notified_at']);
            
            // Unique constraint - doctor can only be dispatched once per treatment request
            $table->unique(['treatment_request_id', 'doctor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_request_doctors');
    }
};
