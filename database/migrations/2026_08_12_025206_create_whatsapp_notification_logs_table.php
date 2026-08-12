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
        Schema::create('whatsapp_notification_logs', function (Blueprint $table) {
            $table->id();
            
            // Link to borrowing request
            $table->foreignId('borrowing_request_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            
            // Notification metadata
            $table->enum('notification_type', [
                'pengajuan_baru',
                'ditolak',
                'disetujui',
                'reminder_h1',
                'dikembalikan'
            ]);
            
            $table->string('recipient_phone', 20);
            $table->json('payload'); // Full payload sent
            
            // Response tracking
            $table->enum('status', ['pending', 'success', 'failed'])
                ->default('pending');
            $table->integer('http_status_code')->nullable();
            $table->text('error_message')->nullable();
            
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('borrowing_request_id');
            $table->index(['notification_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_notification_logs');
    }
};
