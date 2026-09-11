<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('item_returns', function (Blueprint $table) {
            // Tipe peminjam: siswa atau guru
            $table->string('tipe_peminjam', 10)->default('siswa')->after('user_id');
            // Kajur yang seharusnya memverifikasi (hanya untuk tipe guru)
            $table->unsignedBigInteger('kajur_id')->nullable()->after('tipe_peminjam');
            $table->foreign('kajur_id')->references('id')->on('users')->onDelete('set null');
        });

        // Back-fill: setiap ItemReturn yang borrowingRequest-nya tipe_peminjam = 'guru'
        // harus diupdate tipe_peminjam = 'guru' dan kajur_id dari approved_by_kajur_id
        DB::statement("
            UPDATE item_returns ir
            INNER JOIN borrowing_requests br ON br.id = ir.borrowing_request_id
            SET ir.tipe_peminjam = br.tipe_peminjam,
                ir.kajur_id     = br.approved_by_kajur_id
            WHERE br.tipe_peminjam = 'guru'
        ");
    }

    public function down(): void
    {
        Schema::table('item_returns', function (Blueprint $table) {
            $table->dropForeign(['kajur_id']);
            $table->dropColumn(['tipe_peminjam', 'kajur_id']);
        });
    }
};
