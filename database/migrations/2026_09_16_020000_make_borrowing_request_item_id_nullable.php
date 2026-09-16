<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('borrowing_requests')) {
            return;
        }

        if (Schema::hasColumn('borrowing_requests', 'item_id')) {
            DB::statement('ALTER TABLE borrowing_requests MODIFY item_id BIGINT UNSIGNED NULL');
        }

        if (Schema::hasColumn('borrowing_requests', 'quantity')) {
            DB::statement('ALTER TABLE borrowing_requests MODIFY quantity INT NULL DEFAULT 1');
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('borrowing_requests')) {
            return;
        }

        if (Schema::hasColumn('borrowing_requests', 'item_id')) {
            DB::statement('ALTER TABLE borrowing_requests MODIFY item_id BIGINT UNSIGNED NOT NULL');
        }

        if (Schema::hasColumn('borrowing_requests', 'quantity')) {
            DB::statement('ALTER TABLE borrowing_requests MODIFY quantity INT NOT NULL DEFAULT 1');
        }
    }
};
