<?php

namespace App\Services;

use App\Mail\LateBorrowingWarningMail;
use App\Models\BorrowingRequest;
use App\Models\LateWarningLog;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class KajurWarningService
{
    /**
     * Get Eloquent query for overdue loans belonging to the kajur scope:
     * (user.jurusan_id == kajur.jurusan_id) OR (tipe_peminjam == 'guru' AND approved_by_kajur_id == kajur.id)
     */
    public function getOverdueQuery(User|int $kajurOrJurusanId): Builder
    {
        $kajur = $kajurOrJurusanId instanceof User ? $kajurOrJurusanId : User::find($kajurOrJurusanId);
        $jurusanId = (int) ($kajur?->jurusan_id ?? ($kajurOrJurusanId instanceof User ? 0 : (int) $kajurOrJurusanId));
        $kajurId = (int) ($kajur?->id ?? 0);
        $nowJakarta = now()->timezone('Asia/Jakarta');

        return BorrowingRequest::query()
            ->where(function ($scopeQuery) use ($jurusanId, $kajurId) {
                $hasClause = false;
                if ($jurusanId > 0) {
                    $scopeQuery->whereHas('user', function ($uq) use ($jurusanId) {
                        $uq->where('jurusan_id', $jurusanId);
                    });
                    $hasClause = true;
                }

                if ($kajurId > 0) {
                    $method = $hasClause ? 'orWhere' : 'where';
                    $scopeQuery->$method(function ($teacherQuery) use ($kajurId) {
                        $teacherQuery->where('approved_by_kajur_id', $kajurId)
                            ->where(function ($tq) {
                                $tq->where('tipe_peminjam', 'guru')
                                   ->orWhereHas('user', function ($uq) {
                                       $uq->whereHas('roles', function ($rq) {
                                           $rq->where('name', 'guru');
                                       });
                                   });
                            });
                    });
                } elseif (!$hasClause) {
                    $scopeQuery->whereRaw('1 = 0');
                }
            })
            ->where(function ($q) use ($nowJakarta) {
                $q->where('status', BorrowingRequest::STATUS_OVERDUE)
                    ->orWhere(function ($sub) use ($nowJakarta) {
                        $sub->where('status', BorrowingRequest::STATUS_BORROWED)
                            ->where(function ($dateSub) use ($nowJakarta) {
                                $dateSub->whereDate('return_date', '<', $nowJakarta->toDateString())
                                    ->orWhere(function ($timeSub) use ($nowJakarta) {
                                        $timeSub->whereDate('return_date', '=', $nowJakarta->toDateString())
                                            ->whereNotNull('return_time')
                                            ->whereTime('return_time', '<', $nowJakarta->toTimeString());
                                    });
                            });
                    });
            })
            ->with([
                'user.classroom',
                'user.jurusan',
                'itemWithTrashed.category',
                'items.itemWithTrashed.category',
                'lateWarningLogs.sender',
                'latestLateWarning.sender',
            ]);
    }

    /**
     * Check if a specific borrowing request falls strictly into the kajur's authorization scope.
     * Scope: (user.jurusan_id == kajur.jurusan_id) OR (tipe_peminjam == 'guru' AND approved_by_kajur_id == kajur.id)
     */
    public function isBorrowingInKajurScope(BorrowingRequest $borrowing, User $kajur): bool
    {
        $kajurJurusanId = (int) ($kajur->jurusan_id ?? 0);
        $borrowerJurusanId = (int) ($borrowing->user?->jurusan_id ?? 0);
        $isSameJurusan = ($kajurJurusanId > 0 && $borrowerJurusanId === $kajurJurusanId);

        $isTeacherToThisKajur = (
            (int) $borrowing->approved_by_kajur_id === (int) $kajur->id
            && ($borrowing->tipe_peminjam === 'guru' || ($borrowing->user && $borrowing->user->hasRole('guru')))
        );

        return $isSameJurusan || $isTeacherToThisKajur;
    }

    /**
     * Count overdue borrowings that are completely unassigned (no user jurusan_id AND no approved_by_kajur_id).
     */
    public function countUnassignedOverdue(): int
    {
        $nowJakarta = now()->timezone('Asia/Jakarta');

        return BorrowingRequest::query()
            ->where(function ($q) {
                $q->whereNull('approved_by_kajur_id')
                  ->where(function ($uq) {
                      $uq->whereDoesntHave('user')
                         ->orWhereHas('user', function ($sub) {
                             $sub->whereNull('jurusan_id');
                         });
                  });
            })
            ->where(function ($q) use ($nowJakarta) {
                $q->where('status', BorrowingRequest::STATUS_OVERDUE)
                    ->orWhere(function ($sub) use ($nowJakarta) {
                        $sub->where('status', BorrowingRequest::STATUS_BORROWED)
                            ->where(function ($dateSub) use ($nowJakarta) {
                                $dateSub->whereDate('return_date', '<', $nowJakarta->toDateString())
                                    ->orWhere(function ($timeSub) use ($nowJakarta) {
                                        $timeSub->whereDate('return_date', '=', $nowJakarta->toDateString())
                                            ->whereNotNull('return_time')
                                            ->whereTime('return_time', '<', $nowJakarta->toTimeString());
                                    });
                            });
                    });
            })
            ->count();
    }

    /**
     * Calculate days overdue for a borrowing request.
     */
    public function calculateDaysOverdue(BorrowingRequest $borrowing): int
    {
        $nowJakarta = now()->timezone('Asia/Jakarta')->startOfDay();
        $returnDate = $borrowing->return_date ? Carbon::parse($borrowing->return_date)->timezone('Asia/Jakarta')->startOfDay() : $nowJakarta;

        if ($returnDate->lt($nowJakarta)) {
            return (int) $returnDate->diffInDays($nowJakarta);
        }

        return 0;
    }

    /**
     * Generate warning message text.
     */
    public function generateWarningMessage(BorrowingRequest $borrowing, ?User $sender = null): string
    {
        $borrowerName = $borrowing->user?->name ?? 'Peminjam';
        $borrowerType = $borrowing->tipe_peminjam === 'guru' ? 'Guru' : ($borrowing->user?->kelas ?? 'Siswa');
        $itemName = $borrowing->item_display_name;
        $totalQty = $borrowing->totalQuantity();
        $borrowingNumber = '#BR-' . str_pad($borrowing->id, 4, '0', STR_PAD_LEFT);
        
        $returnDate = $borrowing->return_date ? $borrowing->return_date->format('d/m/Y') : '-';
        $returnTime = $borrowing->return_time ? " pukul {$borrowing->return_time}" : '';
        $daysOverdue = $this->calculateDaysOverdue($borrowing);
        $overdueText = $daysOverdue > 0 ? "{$daysOverdue} hari" : "Hari ini (melewati jam pengembalian)";
        
        $senderName = $sender?->name ?? 'Kepala Jurusan';
        $jurusanName = $sender?->jurusan?->nama ?? ($borrowing->user?->jurusan?->nama ?? 'Jurusan');

        return "Assalamu'alaikum Wr. Wb. / Salam Sejahtera,\n"
            . "Yth. {$borrowerName} ({$borrowerType}),\n\n"
            . "⚠️ PEMBERITAHUAN KETERLAMBATAN PENGEMBALIAN BARANG\n"
            . "Berdasarkan sistem SIPBAR SMKN 1 Bangsri, peminjaman barang berikut telah melewati batas waktu:\n\n"
            . "• No. Peminjaman: {$borrowingNumber}\n"
            . "• Barang: {$itemName} ({$totalQty} unit)\n"
            . "• Batas Kembali: {$returnDate}{$returnTime}\n"
            . "• Keterlambatan: {$overdueText}\n\n"
            . "Mohon kesediaannya untuk SEGERA mengembalikan barang tersebut ke petugas / bengkel kejuruan SMKN 1 Bangsri dalam kondisi baik.\n"
            . "Jika terdapat kendala atau kendala teknis, silakan segera konfirmasi kepada Kepala Jurusan.\n\n"
            . "Terima kasih atas perhatian dan kerja samanya.\n\n"
            . "Wassalamu'alaikum Wr. Wb.\n"
            . "— {$senderName}\n"
            . "Kepala Jurusan {$jurusanName}\n"
            . "SMKN 1 Bangsri";
    }

    /**
     * Normalize WhatsApp phone number.
     */
    public function normalizePhoneNumber(?string $number): ?string
    {
        if (empty($number)) {
            return null;
        }

        $number = preg_replace('/[^0-9]/', '', $number);

        if (empty($number)) {
            return null;
        }

        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        } elseif (str_starts_with($number, '+62')) {
            $number = '62' . substr($number, 3);
        }

        return $number;
    }

    /**
     * Generate wa.me WhatsApp link.
     */
    public function generateWhatsAppLink(BorrowingRequest $borrowing, ?User $sender = null): ?string
    {
        $phone = $this->normalizePhoneNumber($borrowing->whatsapp_number ?? $borrowing->user?->phone);

        if (!$phone) {
            return null;
        }

        $message = $this->generateWarningMessage($borrowing, $sender);

        return "https://wa.me/{$phone}?text=" . urlencode($message);
    }

    /**
     * Send warning to borrower via multi-channel (WhatsApp, Email, In-app) with anti-spam check.
     * 
     * @return array [
     *    'status' => 'sent' | 'skipped_antispam' | 'failed',
     *    'message' => string,
     *    'wa_link' => string|null,
     *    'channel' => string,
     * ]
     */
    public function sendWarning(BorrowingRequest $borrowing, User $sender, string $channel = 'whatsapp'): array
    {
        // 1. Pemeriksaan anti-spam: Maksimal 1 peringatan per 24 jam per pengajuan peminjaman
        if (LateWarningLog::hasRecentWarning($borrowing->id)) {
            $latest = LateWarningLog::getLatestFor($borrowing->id);
            $lastSentStr = $latest && $latest->sent_at ? $latest->sent_at->diffForHumans() : 'baru-baru ini';

            return [
                'status' => 'skipped_antispam',
                'message' => "Peringatan untuk peminjaman #BR-{$borrowing->id} sudah dikirim {$lastSentStr} (maksimal 1x per 24 jam).",
                'wa_link' => $this->generateWhatsAppLink($borrowing, $sender),
                'channel' => $channel,
            ];
        }

        $message = $this->generateWarningMessage($borrowing, $sender);
        $phone = $this->normalizePhoneNumber($borrowing->whatsapp_number ?? $borrowing->user?->phone);
        $email = $borrowing->user?->email;
        $daysOverdue = $this->calculateDaysOverdue($borrowing);
        $waLink = $this->generateWhatsAppLink($borrowing, $sender);

        $channelsUsed = [];
        $apiSuccess = false;

        // 2. Coba kirim via WhatsApp API jika terkonfigurasi
        if ($phone) {
            $apiSuccess = $this->sendWhatsAppApi($phone, $message);
            if ($apiSuccess) {
                $channelsUsed[] = 'whatsapp_api';
            } else {
                $channelsUsed[] = 'wa_link';
            }
        }

        // 3. Notifikasi email pelengkap / fallback (sinkron dengan try/catch aman)
        if (!empty($email)) {
            try {
                Mail::to($email)->send(new LateBorrowingWarningMail($borrowing, $sender, $daysOverdue));
                $channelsUsed[] = 'email';
            } catch (\Exception $e) {
                Log::warning('Gagal mengirim email late warning: ' . $e->getMessage(), [
                    'borrowing_id' => $borrowing->id,
                    'email' => $email,
                ]);
            }
        }

        // 4. In-app notification
        if ($borrowing->user_id) {
            try {
                Notification::sendToUser(
                    $borrowing->user_id,
                    'late_warning',
                    "Peringatan: Peminjaman {$borrowing->item_display_name} telah melewati batas waktu. Segera lakukan pengembalian.",
                    [
                        'borrowing_request_id' => $borrowing->id,
                        'days_overdue' => $daysOverdue,
                        'sender_name' => $sender->name,
                    ],
                    'warning'
                );
                $channelsUsed[] = 'in_app';
            } catch (\Exception $e) {
                Log::warning('Gagal membuat notifikasi in-app late warning: ' . $e->getMessage());
            }
        }

        $usedChannelSummary = !empty($channelsUsed) ? implode('+', $channelsUsed) : 'manual_wa';

        // 5. Catat ke LateWarningLog
        LateWarningLog::create([
            'borrowing_request_id' => $borrowing->id,
            'sender_id' => $sender->id,
            'sender_role' => 'kepala_jurusan',
            'channel' => $usedChannelSummary,
            'recipient_phone' => $phone,
            'recipient_email' => $email,
            'status' => 'sent',
            'message_content' => $message,
            'notes' => $apiSuccess ? 'Terkirim via WA API & Email' : 'Fallback link WA & Email',
            'sent_at' => now(),
        ]);

        return [
            'status' => 'sent',
            'message' => "Peringatan berhasil dikirim ke {$borrowing->user?->name}.",
            'wa_link' => $waLink,
            'channel' => $usedChannelSummary,
        ];
    }

    /**
     * Send bulk warnings to all overdue loans in kajur scope (max 50 per execution).
     * 
     * @return array [
     *    'total' => int,
     *    'sent' => int,
     *    'skipped_antispam' => int,
     *    'failed' => int,
     * ]
     */
    public function sendBulkWarnings(User|int $kajurOrJurusanId, ?User $senderUser = null, int $limit = 50): array
    {
        $kajur = $kajurOrJurusanId instanceof User ? $kajurOrJurusanId : ($senderUser ?? User::find($kajurOrJurusanId));
        $sender = $senderUser ?? ($kajurOrJurusanId instanceof User ? $kajurOrJurusanId : User::find($kajurOrJurusanId));

        if (!$kajur || !$sender) {
            return [
                'total' => 0,
                'sent' => 0,
                'skipped_antispam' => 0,
                'failed' => 0,
            ];
        }

        $overdueLoans = $this->getOverdueQuery($kajur)
            ->limit($limit)
            ->get();

        $stats = [
            'total' => $overdueLoans->count(),
            'sent' => 0,
            'skipped_antispam' => 0,
            'failed' => 0,
        ];

        foreach ($overdueLoans as $loan) {
            try {
                $res = $this->sendWarning($loan, $sender, 'bulk_whatsapp');
                if ($res['status'] === 'sent') {
                    $stats['sent']++;
                } elseif ($res['status'] === 'skipped_antispam') {
                    $stats['skipped_antispam']++;
                } else {
                    $stats['failed']++;
                }
            } catch (\Exception $e) {
                $stats['failed']++;
                Log::error('Error during bulk warning send', [
                    'borrowing_id' => $loan->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $stats;
    }

    /**
     * Attempt WhatsApp API delivery if configured.
     */
    private function sendWhatsAppApi(string $phone, string $message): bool
    {
        $baseUrl = config('services.whatsapp.base_url') ?? '';
        $apiKey = config('services.whatsapp.api_key') ?? '';
        $timeout = config('services.whatsapp.timeout', 10);

        if (empty($baseUrl) || empty($apiKey)) {
            return false;
        }

        try {
            $endpoint = rtrim($baseUrl, '/');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
            ])
                ->timeout($timeout)
                ->post($endpoint, [
                    'phone' => $phone,
                    'to' => $phone,
                    'message' => $message,
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::warning('WhatsApp API delivery exception during late warning: ' . $e->getMessage());
            return false;
        }
    }
}
