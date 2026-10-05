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
        Schema::table('users', function (Blueprint $table) {
            // Drop the old jurusan string column since we now use jurusan_id foreign key
            $table->dropColumn('jurusan');
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Restore the jurusan column if needed
            $table->string('jurusan')->nullable()->after('nip');
        });
    }
};
