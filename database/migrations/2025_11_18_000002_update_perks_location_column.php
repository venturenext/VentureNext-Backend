is<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE perks DROP CONSTRAINT IF EXISTS perks_location_check');
            DB::statement("ALTER TABLE perks ALTER COLUMN location DROP DEFAULT");
            DB::statement("ALTER TABLE perks ALTER COLUMN location TYPE VARCHAR(191) USING location::text");
            DB::statement("ALTER TABLE perks ALTER COLUMN location SET DEFAULT 'global'");
        } else {
            DB::statement("ALTER TABLE perks MODIFY location VARCHAR(191) NOT NULL DEFAULT 'global'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting to ENUM would require the original definition; keeping this no-op prevents data loss.
    }
};
