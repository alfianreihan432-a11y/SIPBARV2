<?php

namespace App\Mail;

use App\Models\BorrowingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BorrowingApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public BorrowingRequest $borrowingRequest,
        public string $qrBase64
    ) {}

    public function envelope(): Envelope
    {
        $itemName = $this->borrowingRequest->item?->name ?? 'Barang';

        return new Envelope(
            subject: "[SIPBAR] Pengajuan Disetujui — QR Code Pengambilan: {$itemName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.approved',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
