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
        if (! Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('icon')->nullable();
                $table->string('color')->default('#2563eb');
                $table->text('description')->nullable();
                $table->softDeletes();
                $table->timestamps();
                $table->index('name');
            });
        }

        if (! Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('address')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->softDeletes();
                $table->timestamps();
                $table->index('name');
            });
        }

        if (! Schema::hasTable('locations')) {
            Schema::create('locations', function (Blueprint $table) {
                $table->id();
                $table->string('building');
                $table->string('floor')->nullable();
                $table->string('room')->nullable();
                $table->softDeletes();
                $table->timestamps();
                $table->index(['building', 'floor', 'room']);
            });
        }

        if (! Schema::hasTable('items')) {
            Schema::create('items', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable()->unique();
                $table->string('inventory_number')->nullable()->unique();
                $table->string('name');
                $table->text('description')->nullable();
                $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
                $table->unsignedBigInteger('teacher_id')->nullable();
                $table->string('brand')->nullable();
                $table->string('type')->nullable();
                $table->year('purchase_year')->nullable();
                $table->decimal('price', 12, 2)->default(0);
                $table->string('condition')->default('Baik');
                $table->string('status')->default('Tersedia');
                $table->integer('stock')->default(1);
                $table->string('photo_path')->nullable();
                $table->string('qr_code')->nullable();
                $table->string('barcode')->nullable();
                $table->string('kode_kibb')->nullable()->unique();
                $table->string('nomor_reg')->nullable();
                $table->string('nomor_registrasi')->nullable();
                $table->enum('ukuran', ['Kecil', 'Sedang', 'Besar'])->nullable();
                $table->string('bahan')->nullable();
                $table->integer('tahun_pembelian')->nullable();
                $table->string('asal_usul')->nullable();
                $table->decimal('harga', 15, 2)->nullable();
                $table->softDeletes();
                $table->timestamps();
                $table->index(['name', 'status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('categories');
    }
};
