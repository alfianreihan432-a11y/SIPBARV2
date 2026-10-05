<?php

namespace App\Mail;

use App\Models\BorrowingRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LateBorrowingWarningMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public BorrowingRequest $borrowingRequest,
        public ?User $sender = null,
        public int $daysOverdue = 0
    ) {}

    public function envelope(): Envelope
    {
        $itemName = $this->borrowingRequest->item_display_name;

        return new Envelope(
            subject: "[SIPBAR - Peringatan Keterlambatan] Pengembalian Barang: {$itemName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.late-warning',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
