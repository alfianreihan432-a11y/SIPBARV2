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
        Schema::create('laporan_gurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->string('judul');
            $table->string('periode');
            $table->json('data_laporan');
            $table->text('catatan')->nullable();
            $table->string('status')->default('belum_dibaca'); // 'belum_dibaca', 'sudah_dibaca'
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_gurus');
    }
};
