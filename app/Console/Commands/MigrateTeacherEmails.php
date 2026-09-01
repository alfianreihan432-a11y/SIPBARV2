<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MigrateTeacherEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sipbar:migrate-teacher-emails
                            {--force : Lewati pertanyaan konfirmasi sebelum menjalankan migrasi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrasi format email login Guru menjadi Tanggal Lahir ({YYYYMMDD}@sipbar.sch.id) dari 8 digit pertama NIP';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('================================================================');
        $this->info('   SIPBAR - Migrasi Format Email Guru ke Tanggal Lahir (8 Digit NIP)');
        $this->info('================================================================');
        $this->line('Format Target : <info>{YYYYMMDD}@sipbar.sch.id</info> (contoh: 19840514@sipbar.sch.id)');
        $this->line('Penanganan Duplikat: Otomatis ditambahkan suffix (-2, -3, dst)');
        $this->newLine();

        $teachers = User::role('Guru')
            ->orWhere(function($q) {
                $q->whereNotNull('nip')->where('nip', '!=', '');
            })
            ->get();

        $totalTeachers = $teachers->count();

        if ($totalTeachers === 0) {
            $this->warn('Tidak ditemukan data user Guru di sistem.');
            return Command::SUCCESS;
        }

        $this->info("Ditemukan {$totalTeachers} akun Guru yang akan diproses.");

        if (!$this->option('force')) {
            if (!$this->confirm('Apakah Anda yakin ingin menjalankan migrasi email untuk semua akun guru?', false)) {
                $this->warn('Migrasi dibatalkan.');
                return Command::SUCCESS;
            }
        }

        $this->newLine();
        $this->info('Memproses migrasi email guru...');

        $updatedCount = 0;
        $duplicateCount = 0;
        $migratedDetails = [];

        DB::beginTransaction();

        try {
            // Kita kumpulkan semua email baru terlebih dahulu untuk memastikan tidak ada collision
            $assignedEmails = [];

            foreach ($teachers as $teacher) {
                $baseCode = User::extractBirthDateCode($teacher->nip, $teacher->tanggal_lahir);
                $candidateEmail = $baseCode . '@sipbar.sch.id';
                $isDuplicate = false;

                // Cek bentrok dengan akun yang sudah di-assign di batch ini atau di database (selain dirinya sendiri)
                if (in_array($candidateEmail, $assignedEmails) || User::where('email', $candidateEmail)->where('id', '!=', $teacher->id)->exists()) {
                    $isDuplicate = true;
                    $duplicateCount++;

                    $suffix = 2;
                    while (in_array("{$baseCode}-{$suffix}@sipbar.sch.id", $assignedEmails) || 
                           User::where('email', "{$baseCode}-{$suffix}@sipbar.sch.id")->where('id', '!=', $teacher->id)->exists()) {
                        $suffix++;
                    }
                    $candidateEmail = "{$baseCode}-{$suffix}@sipbar.sch.id";
                }

                $assignedEmails[] = $candidateEmail;

                $oldEmail = $teacher->email;
                if ($oldEmail !== $candidateEmail) {
                    $teacher->email = $candidateEmail;
                    $teacher->saveQuietly();
                    $updatedCount++;
                }

                $migratedDetails[] = [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'nip' => $teacher->nip,
                    'old_email' => $oldEmail,
                    'new_email' => $candidateEmail,
                    'is_duplicate' => $isDuplicate,
                ];
            }

            DB::commit();

            $this->newLine();
            $this->info('✓ Proses migrasi email guru selesai!');
            $this->table(
                ['Metrik', 'Nilai'],
                [
                    ['Total Akun Guru', $totalTeachers],
                    ['Akun Diperbarui', $updatedCount],
                    ['Kasus Duplikat Tanggal Lahir', $duplicateCount],
                ]
            );

            // Tampilkan sampel hasil
            $this->newLine();
            $this->info('Sampel 10 Akun Guru Setelah Migrasi:');
            $sampleRows = array_map(function($r) {
                return [
                    $r['id'],
                    mb_substr($r['name'], 0, 25),
                    $r['nip'] ?? '-',
                    $r['old_email'],
                    $r['new_email'],
                    $r['is_duplicate'] ? 'Ya (Suffix)' : 'Tidak',
                ];
            }, array_slice($migratedDetails, 0, 10));

            $this->table(
                ['ID', 'Nama', 'NIP', 'Email Lama', 'Email Baru', 'Duplikat'],
                $sampleRows
            );

            Log::info('MigrateTeacherEmails completed', [
                'total' => $totalTeachers,
                'updated' => $updatedCount,
                'duplicates' => $duplicateCount,
            ]);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Terjadi kesalahan saat migrasi: ' . $e->getMessage());
            Log::error('MigrateTeacherEmails failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return Command::FAILURE;
        }
    }
}
