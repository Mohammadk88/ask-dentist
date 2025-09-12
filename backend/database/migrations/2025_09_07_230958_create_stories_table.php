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
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->enum('owner_type', ['clinic', 'doctor']);
            $table->uuid('owner_id');
            $table->json('media'); // {type: 'image|video', url: '', thumbnail: '', duration: null}
            $table->text('caption')->nullable();
            $table->string('lang', 5)->default('en');
            $table->timestamp('starts_at');
            $table->timestamp('expires_at');
            $table->boolean('is_ad')->default(false);
            $table->timestamps();

            // Indexes
            $table->index(['owner_type', 'owner_id']);
            $table->index('expires_at');
            $table->index(['starts_at', 'expires_at']);
            $table->index('is_ad');

            // Ensure valid references based on owner_type
            $table->index('owner_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};
