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
        // 1. Pastikan constraint unik pada jurusan_id di laporan_jurusans sehingga hanya ada 1 baris aktif per jurusan
        Schema::table('laporan_jurusans', function (Blueprint $table) {
            $table->unique('jurusan_id');
        });

        // 2. Buat tabel riwayat untuk mengarsipkan pengajuan sebelumnya
        Schema::create('laporan_jurusan_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_jurusan_id')->constrained('laporan_jurusans')->onDelete('cascade');
            $table->foreignId('jurusan_id')->constrained('jurusans')->onDelete('cascade');
            $table->date('periode_awal');
            $table->date('periode_akhir');
            $table->foreignId('dikirim_oleh')->constrained('users')->onDelete('cascade');
            $table->string('status', 50)->default('pending_review');
            $table->text('catatan_admin')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_jurusan_histories');

        Schema::table('laporan_jurusans', function (Blueprint $table) {
            $table->dropUnique(['jurusan_id']);
        });
    }
};
