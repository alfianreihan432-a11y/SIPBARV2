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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('type')->default('text'); // text, image, json
            $table->text('value')->nullable();
            $table->string('group')->default('general'); // general, hero, features, stats, about, footer
            $table->string('label')->nullable(); // Human-readable label for admin interface
            $table->text('description')->nullable(); // Description for admin interface
            $table->timestamps();
            
            // Index for faster lookups by group
            $table->index('group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
