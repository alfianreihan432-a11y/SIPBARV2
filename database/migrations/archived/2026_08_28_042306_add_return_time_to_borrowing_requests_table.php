<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::table('borrowing_requests', function (Blueprint $table) {
            $table->time('return_time')->nullable()->after('return_date');
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::table('borrowing_requests', function (Blueprint $table) {
            $table->dropColumn('return_time');
        });
    }
};
