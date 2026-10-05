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
        Schema::table('laporan_jurusans', function (Blueprint $table) {
            $table->index('jurusan_id', 'laporan_jurusans_jurusan_id_index');
            $table->dropUnique('laporan_jurusans_jurusan_id_unique');
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::table('laporan_jurusans', function (Blueprint $table) {
            $table->unique('jurusan_id', 'laporan_jurusans_jurusan_id_unique');
            $table->dropIndex('laporan_jurusans_jurusan_id_index');
        });
    }
};
