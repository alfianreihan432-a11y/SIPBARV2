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
        Schema::table('borrowing_requests', function (Blueprint $table) {
            // QR Token (denormalized for fast lookup)
            $table->string('qr_token', 64)->nullable()->unique()
                ->after('rejection_reason');
            
            // H-1 Reminder tracking
            $table->timestamp('reminder_sent_at')->nullable()
                ->after('qr_token');
            
            // Track who performed checkout/checkin
            $table->foreignId('checkout_by')->nullable()
                ->after('borrowed_at')
                ->constrained('users')
                ->onDelete('set null');
            
            $table->foreignId('checkin_by')->nullable()
                ->after('returned_at')
                ->constrained('users')
                ->onDelete('set null');
            
            // Indexes for performance
            $table->index(['status', 'return_date'], 'idx_status_return_date');
            $table->index('qr_token', 'idx_qr_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowing_requests', function (Blueprint $table) {
            $table->dropForeign(['checkout_by']);
            $table->dropForeign(['checkin_by']);
            $table->dropIndex('idx_status_return_date');
            $table->dropIndex('idx_qr_token');
            $table->dropColumn([
                'qr_token',
                'reminder_sent_at',
                'checkout_by',
                'checkin_by'
            ]);
        });
    }
};
