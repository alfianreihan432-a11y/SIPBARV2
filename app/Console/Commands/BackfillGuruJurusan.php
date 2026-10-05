<?php

namespace App\Console\Commands;

use App\Models\BorrowingRequest;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Console\Command;

class BackfillGuruJurusan extends Command
{
    /**
     * Nama dan signature dari perintah konsol.
     *
     * @var string
     */
    protected $signature = 'guru:backfill-jurusan {--apply : Terapkan perubahan ke database}';

    /**
     * Deskripsi dari perintah konsol.
     *
     * @var string
     */
    protected $description = 'Backfill jurusan_id untuk user ber-role guru berdasarkan riwayat kajur tujuan peminjaman';

    /**
     * Jalankan perintah konsol.
     */
    public function handle(): int
    {
        $isApply = (bool) $this->option('apply');

        $this->info($isApply
            ? 'Memulai proses BACKFILL jurusan guru (Mode: APPLY / Langsung Update Database)...'
            : 'Memulai proses BACKFILL jurusan guru (Mode: DRY-RUN / Simulasi Tanpa Menyimpan)...'
        );

        // Ambil semua user dengan role 'guru' yang memiliki jurusan_id NULL
        $teachers = User::role('guru')
            ->whereNull('jurusan_id')
            ->orderBy('name')
            ->get();

        if ($teachers->isEmpty()) {
            $this->info('Tidak ada user guru dengan jurusan_id NULL.');
            return Command::SUCCESS;
        }

        $rows = [];
        $totalChecked = $teachers->count();
        $countFillable = 0;
        $countAmbiguous = 0;
        $countNoData = 0;
        $countApplied = 0;

        foreach ($teachers as $idx => $teacher) {
            // Ambil seluruh permohonan peminjaman guru yang memiliki kajur tujuan
            $borrowings = BorrowingRequest::where('user_id', $teacher->id)
                ->whereNotNull('approved_by_kajur_id')
                ->with('approvedByKajur.jurusan')
                ->get();

            if ($borrowings->isEmpty()) {
                $countNoData++;
                $rows[] = [
                    $idx + 1,
                    $teacher->name,
                    $teacher->nip ?? '-',
                    '—',
                    '<fg=yellow>Tidak Ada Data Peminjaman</>',
                ];
                continue;
            }

            // Kumpulkan ID jurusan dari kajur-kajur tujuan
            $kajurJurusanIds = $borrowings->map(function ($b) {
                return $b->approvedByKajur?->jurusan_id;
            })->filter()->unique()->values();

            if ($kajurJurusanIds->isEmpty()) {
                $countNoData++;
                $rows[] = [
                    $idx + 1,
                    $teacher->name,
                    $teacher->nip ?? '-',
                    '—',
                    '<fg=yellow>Kajur Tujuan Tanpa Jurusan</>',
                ];
                continue;
            }

            if ($kajurJurusanIds->count() > 1) {
                // Ambigu: meminjam ke kajur dari lebih dari 1 jurusan berbeda
                $countAmbiguous++;
                $jurusanNames = Jurusan::whereIn('id', $kajurJurusanIds)->pluck('nama')->implode(', ');
                $rows[] = [
                    $idx + 1,
                    $teacher->name,
                    $teacher->nip ?? '-',
                    $jurusanNames,
                    '<fg=red>Ambigu (>1 Jurusan)</>',
                ];
                continue;
            }

            // Valid 1 Jurusan
            $targetJurusanId = $kajurJurusanIds->first();
            $targetJurusan = Jurusan::find($targetJurusanId);
            $jurusanName = $targetJurusan ? $targetJurusan->nama : "ID: {$targetJurusanId}";

            $countFillable++;

            if ($isApply) {
                $teacher->jurusan_id = $targetJurusanId;
                $teacher->save();
                $countApplied++;

                $rows[] = [
                    $idx + 1,
                    $teacher->name,
                    $teacher->nip ?? '-',
                    $jurusanName,
                    '<fg=green>Berhasil Diperbarui</>',
                ];
            } else {
                $rows[] = [
                    $idx + 1,
                    $teacher->name,
                    $teacher->nip ?? '-',
                    $jurusanName,
                    '<fg=cyan>Akan Diisi</>',
                ];
            }
        }

        $this->table(
            ['No', 'Nama Guru', 'NIP', 'Jurusan Pemetaan', 'Status'],
            $rows
        );

        $this->newLine();
        $this->line("Total guru diperiksa: <options=bold>{$totalChecked}</>");
        $this->line("Dapat dipetakan: <fg=green>{$countFillable}</>");
        $this->line("Kasus ambigu (dilewati): <fg=red>{$countAmbiguous}</>");
        $this->line("Tanpa riwayat kajur/jurusan: <fg=yellow>{$countNoData}</>");

        if ($isApply) {
            $this->info("✓ Selesai: Berhasil memperbarui {$countApplied} guru.");
        } else {
            $this->warn("! Mode DRY-RUN: Tidak ada perubahan database yang disimpan. Jalankan dengan flag --apply untuk menerapkan.");
        }

        return Command::SUCCESS;
    }
}
