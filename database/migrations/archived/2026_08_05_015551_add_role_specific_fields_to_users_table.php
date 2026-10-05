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
            $table->string('nis')->nullable()->after('email');
            $table->string('kelas')->nullable()->after('nis');
            $table->text('alamat')->nullable()->after('kelas');
            $table->date('tanggal_lahir')->nullable()->after('alamat');
            $table->string('nip')->nullable()->after('tanggal_lahir');
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nis', 'kelas', 'alamat', 'tanggal_lahir', 'nip']);
        });
    }
};
