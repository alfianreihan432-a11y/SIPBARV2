<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('notifications') && !Schema::hasColumn('notifications', 'category')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->string('category')->default('notification')->after('type');
                $table->index(['user_id', 'category', 'is_read']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('notifications') && Schema::hasColumn('notifications', 'category')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropIndex(['user_id', 'category', 'is_read']);
                $table->dropColumn('category');
            });
        }
    }
};
