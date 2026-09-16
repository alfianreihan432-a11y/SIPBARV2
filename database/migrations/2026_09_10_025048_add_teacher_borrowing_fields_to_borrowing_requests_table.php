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
        if (! Schema::hasTable('borrowing_requests') || ! Schema::hasTable('users')) {
            return;
        }

        if (Schema::hasColumn('borrowing_requests', 'tipe_peminjam')) {
            return;
        }

        Schema::table('borrowing_requests', function (Blueprint $table) {
            $table->enum('tipe_peminjam', ['siswa', 'guru'])->default('siswa')->after('user_id');
            $table->foreignId('approved_by_kajur_id')->nullable()->after('teacher_id')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowing_requests', function (Blueprint $table) {
            $table->dropForeign(['approved_by_kajur_id']);
            $table->dropColumn(['tipe_peminjam', 'approved_by_kajur_id']);
        });
    }
};
