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
        if (! Schema::hasTable('borrowing_requests')) {
            return;
        }

        Schema::table('borrowing_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('borrowing_requests', 'whatsapp_number')) {
                $table->string('whatsapp_number', 25)->nullable()->after('notes');
            }
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        if (! Schema::hasTable('borrowing_requests')) {
            return;
        }

        Schema::table('borrowing_requests', function (Blueprint $table) {
            if (Schema::hasColumn('borrowing_requests', 'whatsapp_number')) {
                $table->dropColumn('whatsapp_number');
            }
        });
    }
};
