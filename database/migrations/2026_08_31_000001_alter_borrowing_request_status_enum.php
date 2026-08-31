<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE borrowing_requests MODIFY COLUMN status ENUM('pending', 'cancelled', 'approved', 'rejected', 'qr_ready', 'borrowed', 'returned', 'overdue') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE borrowing_requests MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'qr_ready', 'borrowed', 'returned', 'overdue') NOT NULL DEFAULT 'pending'");
    }
};
