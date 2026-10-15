<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('borrowing_requests')) {
            return;
        }

        Schema::table('borrowing_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('borrowing_requests', 'lender_type')) {
                $table->string('lender_type', 10)->default('admin')->after('tipe_peminjam');
            }

            if (! Schema::hasColumn('borrowing_requests', 'lender_kajur_id')) {
                $table->foreignId('lender_kajur_id')->nullable()->after('lender_type')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('borrowing_requests')) {
            return;
        }

        Schema::table('borrowing_requests', function (Blueprint $table) {
            if (Schema::hasColumn('borrowing_requests', 'lender_kajur_id')) {
                $table->dropConstrainedForeignId('lender_kajur_id');
            }

            if (Schema::hasColumn('borrowing_requests', 'lender_type')) {
                $table->dropColumn('lender_type');
            }
        });
    }
};
