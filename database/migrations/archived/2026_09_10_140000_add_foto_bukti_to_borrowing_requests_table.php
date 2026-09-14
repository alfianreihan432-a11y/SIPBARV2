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
        Schema::table('borrowing_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('borrowing_requests', 'foto_bukti')) {
                $table->string('foto_bukti')->nullable()->after('return_notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowing_requests', function (Blueprint $table) {
            if (Schema::hasColumn('borrowing_requests', 'foto_bukti')) {
                $table->dropColumn('foto_bukti');
            }
        });
    }
};
