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
        Schema::create('doctors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->string('license_number')->unique();
            $table->enum('specialty', [
                'general',
                'orthodontics',
                'oral_surgery',
                'endodontics',
                'periodontics',
                'prosthodontics',
                'pediatric',
                'cosmetic',
                'implantology'
            ]);
            $table->text('bio')->nullable();
            $table->jsonb('qualifications')->nullable(); // Education, certifications, etc.
            $table->integer('years_experience')->default(0);
            $table->jsonb('languages')->nullable(); // Languages spoken
            $table->decimal('rating', 3, 2)->default(0); // 0.00 to 5.00
            $table->integer('total_reviews')->default(0);
            $table->boolean('accepts_emergency')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('user_id');
            $table->index('specialty');
            $table->index('verified_at');
            $table->index(['specialty', 'verified_at']);
            $table->index(['rating', 'total_reviews']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
