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
        // Add 'cancelled' to the enum constraint for PostgreSQL
        DB::statement("ALTER TABLE rentals DROP CONSTRAINT IF EXISTS rentals_status_check");
        DB::statement("ALTER TABLE rentals ADD CONSTRAINT rentals_status_check CHECK (status IN ('pending', 'active', 'extended', 'returned', 'overdue', 'cancelled'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE rentals DROP CONSTRAINT IF EXISTS rentals_status_check");
        DB::statement("ALTER TABLE rentals ADD CONSTRAINT rentals_status_check CHECK (status IN ('pending', 'active', 'extended', 'returned', 'overdue'))");
    }
};
