<?php

namespace App\Mail;

use App\Models\BorrowingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class NewBorrowingRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Magic link URL valid selama 3 hari.
     */
    public string $approvalUrl;

    public function __construct(
        public BorrowingRequest $borrowingRequest
    ) {
        // Dibuat di constructor agar URL ter-generate saat job di-dispatch,
        // bukan saat di-serialize (hindari race condition signature)
        $this->approvalUrl = URL::temporarySignedRoute(
            'approval.show',
            now()->addDays(3),
            ['borrowingRequest' => $borrowingRequest->id]
        );
    }

    public function envelope(): Envelope
    {
        $studentName = $this->borrowingRequest->user?->name ?? 'Siswa';
        $itemName    = $this->borrowingRequest->item?->name ?? 'Barang';

        return new Envelope(
            subject: "[SIPBAR] Pengajuan Peminjaman Baru: {$itemName} oleh {$studentName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-request',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
