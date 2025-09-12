<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->boolean('is_promoted')->default(false);
            $table->timestamp('promoted_until')->nullable();
            $table->decimal('rating_avg', 3, 2)->default(0.00);
            $table->integer('rating_count')->default(0);
            $table->string('cover_path')->nullable(); // Cover image for clinic

            // Indexes
            $table->index('is_promoted');
            $table->index('promoted_until'); // For filtering active promotions
            $table->index(['rating_avg', 'rating_count']); // For sorting by rating
            $table->index(['is_promoted', 'promoted_until']); // Composite for promotion queries
        });

        // Create partial index for active promotions (PostgreSQL specific)
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('CREATE INDEX clinics_active_promotions_idx ON clinics (promoted_until) WHERE promoted_until IS NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropIndex(['is_promoted', 'promoted_until']);
            $table->dropIndex(['rating_avg', 'rating_count']);
            $table->dropIndex(['promoted_until']);
            $table->dropIndex(['is_promoted']);

            $table->dropColumn([
                'is_promoted',
                'promoted_until',
                'rating_avg',
                'rating_count',
                'cover_path'
            ]);
        });

        // Drop partial index if it exists
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS clinics_active_promotions_idx');
        }
    }
};
