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
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "X PM 2"
            $table->string('kode')->nullable(); // Classroom code if available
            $table->integer('status')->default(1); // 1 = active, 0 = inactive
            $table->boolean('is_pkl')->default(false); // PKL status
            $table->timestamps();
            $table->softDeletes();

            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
