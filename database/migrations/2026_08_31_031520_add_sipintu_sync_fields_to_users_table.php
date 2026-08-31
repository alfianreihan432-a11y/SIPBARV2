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
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('sipintu_synced_at')->nullable()->after('updated_at');
            $table->string('data_source')->default('manual')->after('sipintu_synced_at')->comment('Source: manual or sipintu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['sipintu_synced_at', 'data_source']);
        });
    }
};
