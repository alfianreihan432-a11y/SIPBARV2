<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE borrowing_requests MODIFY COLUMN status ENUM('pending', 'cancelled', 'approved', 'rejected', 'qr_ready', 'borrowed', 'returned', 'overdue') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE borrowing_requests MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'qr_ready', 'borrowed', 'returned', 'overdue') NOT NULL DEFAULT 'pending'");
    }
};
