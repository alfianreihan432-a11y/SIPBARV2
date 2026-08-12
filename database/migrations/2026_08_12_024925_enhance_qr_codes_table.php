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
        Schema::table('qr_codes', function (Blueprint $table) {
            // Track scan count for analytics
            $table->integer('scan_count')->default(0)
                ->after('scanned_at');
            
            // Track last scan (keep scanned_at as first_scanned_at)
            $table->timestamp('last_scanned_at')->nullable()
                ->after('scan_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->dropColumn(['scan_count', 'last_scanned_at']);
        });
    }
};
