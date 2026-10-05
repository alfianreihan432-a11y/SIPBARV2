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
     * Notify teacher about a new borrowing request.
     * Email berisi detail pengajuan + magic link approval (berlaku 3 hari).
     */
    public function notifyNewRequest(BorrowingRequest $request): void
    {
        try {
            $teacherEmail = $request->teacher?->email;

            if (empty($teacherEmail)) {
                Log::warning('Email notifikasi pengajuan baru dilewati: guru tidak memiliki email', [
                    'borrowing_request_id' => $request->id,
                    'teacher_id'           => $request->teacher_id,
                ]);
                return;
            }

            Mail::to($teacherEmail)->queue(new NewBorrowingRequestMail($request));

            Log::info('Email notifikasi pengajuan baru diantrekan', [
                'borrowing_request_id' => $request->id,
                'to'                   => $teacherEmail,
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
