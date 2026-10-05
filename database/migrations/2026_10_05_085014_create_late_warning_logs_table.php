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
        Schema::create('late_warning_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrowing_request_id')->constrained('borrowing_requests')->onDelete('cascade');
            $table->foreignId('sender_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sender_role', 50)->default('kepala_jurusan');
            $table->string('channel', 20)->default('whatsapp'); // 'whatsapp', 'email', 'in_app', 'bulk_whatsapp'
            $table->string('recipient_phone', 25)->nullable();
            $table->string('recipient_email', 150)->nullable();
            $table->string('status', 30)->default('sent'); // 'sent', 'queued', 'skipped_antispam', 'failed'
            $table->text('message_content')->nullable();
            $table->string('notes')->nullable();
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamps();

            $table->index(['borrowing_request_id', 'sent_at']);
            $table->index(['sender_id', 'sent_at']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('late_warning_logs');
    }
};
