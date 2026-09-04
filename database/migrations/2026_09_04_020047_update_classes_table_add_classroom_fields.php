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
        Schema::table('classes', function (Blueprint $table) {
            $table->string('kode')->nullable()->after('name');
            $table->foreignId('class_leader_id')->nullable()->after('class_leader_nis')->constrained('users')->nullOnDelete();
            $table->foreignId('class_advisor_id')->nullable()->after('class_leader_id')->constrained('users')->nullOnDelete();
            $table->string('class_advisor_phone')->nullable()->after('homeroom_teacher');
            $table->boolean('is_pkl')->default(false)->after('class_advisor_phone');
            $table->integer('status')->default(1)->after('is_pkl');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn(['kode', 'class_leader_id', 'class_advisor_id', 'class_advisor_phone', 'is_pkl', 'status']);
        });
    }
};
