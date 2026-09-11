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
        // 1. Ensure unique constraint on jurusan_id in laporan_jurusans so only 1 active row per jurusan exists
        Schema::table('laporan_jurusans', function (Blueprint $table) {
            $table->unique('jurusan_id');
        });

        // 2. Create history table to archive previous submissions
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
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_jurusan_histories');

        Schema::table('laporan_jurusans', function (Blueprint $table) {
            $table->dropUnique(['jurusan_id']);
        });
    }
};
