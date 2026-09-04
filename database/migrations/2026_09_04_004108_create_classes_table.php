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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('kode')->unique();
            $table->foreignId('class_leader_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('class_advisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('class_advisor_phone')->nullable();
            $table->boolean('is_pkl')->default(false);
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
