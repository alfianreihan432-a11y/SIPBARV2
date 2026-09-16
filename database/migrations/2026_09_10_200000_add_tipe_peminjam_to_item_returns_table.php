<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('item_returns') || ! Schema::hasTable('borrowing_requests')) {
            return;
        }

        if (Schema::hasColumn('item_returns', 'tipe_peminjam')) {
            return;
        }

        Schema::table('item_returns', function (Blueprint $table) {
            // Tipe peminjam: siswa atau guru
            $table->string('tipe_peminjam', 10)->default('siswa')->after('user_id');
            // Kajur yang seharusnya memverifikasi (hanya untuk tipe guru)
            $table->unsignedBigInteger('kajur_id')->nullable()->after('tipe_peminjam');
            $table->foreign('kajur_id')->references('id')->on('users')->onDelete('set null');
        });

        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("
                UPDATE item_returns ir
                INNER JOIN borrowing_requests br ON br.id = ir.borrowing_request_id
                SET ir.tipe_peminjam = br.tipe_peminjam,
                    ir.kajur_id     = br.approved_by_kajur_id
                WHERE br.tipe_peminjam = 'guru'
            ");
        }
    }

    public function down(): void
    {
        Schema::table('item_returns', function (Blueprint $table) {
            $table->dropForeign(['kajur_id']);
            $table->dropColumn(['tipe_peminjam', 'kajur_id']);
        });
    }
};
