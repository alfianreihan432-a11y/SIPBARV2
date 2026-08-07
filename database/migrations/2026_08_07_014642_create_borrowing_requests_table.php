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
        Schema::create('borrowing_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('quantity')->default(1);
            $table->text('purpose')->nullable();
            $table->date('borrow_date');
            $table->date('return_date');
            $table->text('notes')->nullable();
            $table->enum('status', [
                'pending',           // Menunggu Persetujuan Guru
                'approved',          // Disetujui Guru
                'rejected',          // Ditolak Guru
                'qr_ready',          // QR Code Siap Digunakan
                'borrowed',          // Barang Dipinjam
                'returned',          // Dikembalikan
                'overdue'           // Terlambat
            ])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('borrowed_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->enum('return_condition', ['good', 'damaged', 'lost'])->nullable();
            $table->text('return_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowing_requests');
    }
};
