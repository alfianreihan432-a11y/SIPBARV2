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
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE borrowing_requests MODIFY COLUMN return_condition VARCHAR(50) NULL");
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE borrowing_requests MODIFY COLUMN return_condition ENUM('good','damaged','lost') NULL");
    }
};
