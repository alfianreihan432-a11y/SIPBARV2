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
        Schema::create('laporan_jurusans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jurusan_id')->constrained('jurusans')->onDelete('cascade');
            $table->date('periode_awal');
            $table->date('periode_akhir');
            $table->foreignId('dikirim_oleh')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending_review', 'disetujui', 'ditolak'])->default('pending_review');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_jurusans');
    }
};
