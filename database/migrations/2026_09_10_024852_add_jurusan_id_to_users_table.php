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
        if (! Schema::hasTable('users') || ! Schema::hasTable('jurusans')) {
            return;
        }

        if (Schema::hasColumn('users', 'jurusan_id')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('jurusan_id')->nullable()->after('phone')->constrained('jurusans')->nullOnDelete();
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['jurusan_id']);
            $table->dropColumn('jurusan_id');
        });
    }
};
