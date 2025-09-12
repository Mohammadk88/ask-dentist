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
        // Check if index already exists before creating it
        $database = config('database.default');
        $indexExists = false;

        if ($database === 'sqlite') {
            $indexExists = DB::select("SELECT name FROM sqlite_master WHERE type='index' AND name='idx_treatment_request_status'");
        } elseif ($database === 'pgsql') {
            $indexExists = DB::select("SELECT indexname FROM pg_indexes WHERE indexname = 'idx_treatment_request_status'");
        }

        if (empty($indexExists)) {
            Schema::table('treatment_plans', function (Blueprint $table) {
                // Add partial unique index to ensure only one accepted plan per treatment request
                $table->index(['treatment_request_id', 'status'], 'idx_treatment_request_status');
            });
        }

        // Add the partial unique constraint using raw SQL for better control
        $uniqueIndexExists = false;

        if ($database === 'sqlite') {
            $uniqueIndexExists = DB::select("SELECT name FROM sqlite_master WHERE type='index' AND name='unique_accepted_plan_per_request'");
        } elseif ($database === 'pgsql') {
            $uniqueIndexExists = DB::select("SELECT indexname FROM pg_indexes WHERE indexname = 'unique_accepted_plan_per_request'");
        }

        if (empty($uniqueIndexExists)) {
            if ($database === 'sqlite') {
                DB::statement("
                    CREATE UNIQUE INDEX unique_accepted_plan_per_request
                    ON treatment_plans (treatment_request_id)
                    WHERE status = 'accepted' AND deleted_at IS NULL
                ");
            } elseif ($database === 'pgsql') {
                DB::statement("
                    CREATE UNIQUE INDEX unique_accepted_plan_per_request
                    ON treatment_plans (treatment_request_id)
                    WHERE status = 'accepted' AND deleted_at IS NULL
                ");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('treatment_plans', function (Blueprint $table) {
            $table->dropIndex('idx_treatment_request_status');
        });

        DB::statement("DROP INDEX IF EXISTS unique_accepted_plan_per_request");
    }
};
