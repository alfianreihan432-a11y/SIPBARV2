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
        if (! Schema::hasTable('items')) {
            return;
        }

        Schema::table('items', function (Blueprint $table) {
            $table->string('nomor_registrasi')->nullable()->after('nomor_reg');
            $table->enum('ukuran', ['Kecil', 'Sedang', 'Besar'])->nullable()->after('nomor_registrasi');
            $table->string('bahan')->nullable()->after('ukuran');
            $table->integer('tahun_pembelian')->nullable()->after('bahan');
            $table->string('asal_usul')->nullable()->after('tahun_pembelian');
            $table->decimal('harga', 15, 2)->nullable()->after('asal_usul');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['nomor_registrasi', 'ukuran', 'bahan', 'tahun_pembelian', 'asal_usul', 'harga']);
        });
    }
};
