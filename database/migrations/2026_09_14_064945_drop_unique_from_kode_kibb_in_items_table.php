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
        if (! Schema::hasTable('items')) {
            return;
        }

        Schema::table('items', function (Blueprint $table) {
            $table->dropUnique(['kode_kibb']);
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->unique('kode_kibb');
        });
    }
};
