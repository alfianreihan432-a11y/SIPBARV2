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
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('sipintu_synced_at')->nullable()->after('updated_at');
            $table->string('data_source')->default('manual')->after('sipintu_synced_at')->comment('Source: manual or sipintu');
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['sipintu_synced_at', 'data_source']);
        });
    }
};
