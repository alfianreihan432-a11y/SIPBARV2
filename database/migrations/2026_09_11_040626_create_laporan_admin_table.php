<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan_admin', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->date('periode_awal');
            $table->date('periode_akhir');
            $table->foreignId('dikirim_oleh')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending_review', 'disetujui', 'ditolak'])->default('pending_review');
            $table->text('catatan_superadmin')->nullable();
            $table->json('data_rekap')->nullable(); // Store consolidated report data
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_admin');
    }
};
