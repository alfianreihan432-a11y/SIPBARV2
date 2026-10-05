<?php

namespace App\Services;

use App\Mail\BorrowingApprovedMail;
use App\Mail\BorrowingRejectedMail;
use App\Mail\NewBorrowingRequestMail;
use App\Models\BorrowingRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailNotificationService
{
    /**
     * Notify teacher or kajur about a new borrowing request.
     * Jika kajur_tujuan_id diisi, kirim email ke kajur tersebut.
     * Jika teacher_id diisi, kirim email ke guru pembimbing.
     */
    public function notifyNewRequest(BorrowingRequest $request): void
    {
        try {
            // Tentukan penerima: kajur atau guru pembimbing
            $recipientEmail = null;
            $recipientLabel = 'guru';

            if ($request->kajur_tujuan_id) {
                $recipientEmail = $request->kajurTujuan?->email;
                $recipientLabel = 'kajur';
            } elseif ($request->teacher_id) {
                $recipientEmail = $request->teacher?->email;
                $recipientLabel = 'guru';
            }

            if (empty($recipientEmail)) {
                Log::warning('Email notifikasi pengajuan baru dilewati: penerima tidak memiliki email', [
                    'borrowing_request_id' => $request->id,
                    'teacher_id'           => $request->teacher_id,
                    'kajur_tujuan_id'      => $request->kajur_tujuan_id,
                    'recipient_label'      => $recipientLabel,
                ]);
                return;
            }

            Mail::to($recipientEmail)->queue(new NewBorrowingRequestMail($request));

            Log::info('Email notifikasi pengajuan baru diantrekan', [
                'borrowing_request_id' => $request->id,
                'to'                   => $recipientEmail,
                'recipient_type'       => $recipientLabel,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengantrekan email notifikasi pengajuan baru', [
                'borrowing_request_id' => $request->id,
                'error'                => $e->getMessage(),
                'trace'                => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Notify student that their borrowing request has been approved.
     * Email berisi info barang dan gambar QR Code (base64).
     */
    public function notifyApproved(BorrowingRequest $request, string $qrBase64): void
    {
        try {
            $studentEmail = $request->user?->email;

            if (empty($studentEmail)) {
                Log::warning('Email notifikasi disetujui dilewati: siswa tidak memiliki email', [
                    'borrowing_request_id' => $request->id,
                    'user_id'              => $request->user_id,
                ]);
                return;
            }

            Mail::to($studentEmail)->queue(new BorrowingApprovedMail($request, $qrBase64));

            Log::info('Email notifikasi disetujui diantrekan', [
                'borrowing_request_id' => $request->id,
                'to'                   => $studentEmail,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengantrekan email notifikasi disetujui', [
                'borrowing_request_id' => $request->id,
                'error'                => $e->getMessage(),
                'trace'                => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Notify student that their borrowing request has been rejected.
     * Email berisi alasan penolakan dari guru.
     */
    public function notifyRejected(BorrowingRequest $request): void
    {
        try {
            $studentEmail = $request->user?->email;

            if (empty($studentEmail)) {
                Log::warning('Email notifikasi ditolak dilewati: siswa tidak memiliki email', [
                    'borrowing_request_id' => $request->id,
                    'user_id'              => $request->user_id,
                ]);
                return;
            }

            Mail::to($studentEmail)->queue(new BorrowingRejectedMail($request));

            Log::info('Email notifikasi ditolak diantrekan', [
                'borrowing_request_id' => $request->id,
                'to'                   => $studentEmail,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengantrekan email notifikasi ditolak', [
                'borrowing_request_id' => $request->id,
                'error'                => $e->getMessage(),
                'trace'                => $e->getTraceAsString(),
            ]);
        }
    }
}
