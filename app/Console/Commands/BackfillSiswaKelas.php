<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\SipintuService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class BackfillSiswaKelas extends Command
{
    protected $signature = 'siswa:backfill-kelas
                            {--batch=50 : Number of students to process per batch}
                            {--delay=100 : Delay in milliseconds between API calls (rate limiting)}
                            {--dry-run : Show what would be updated without actually updating}';

    protected $description = 'Backfill kolom kelas untuk siswa yang kosong dari data SiPintu berdasarkan NIS';

    protected SipintuService $sipintu;

    public function __construct(SipintuService $sipintu)
    {
        parent::__construct();
        $this->sipintu = $sipintu;
    }

    public function handle(): int
    {
        $this->info('=== Backfill Kelas Siswa dari SiPintu ===');
        $this->newLine();

        $batchSize = (int) $this->option('batch');
        $delay = (int) $this->option('delay');
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->warn('MODE: DRY-RUN (tidak akan mengupdate database)');
            $this->newLine();
        }

        // Get student role
        $studentRole = Role::where('name', 'siswa')->first();
        if (!$studentRole) {
            $this->error('Role "siswa" tidak ditemukan.');
            return self::FAILURE;
        }

        // Count students without kelas
        $totalWithoutKelas = User::whereHas('roles', function($q) {
            $q->where('name', 'siswa');
        })->where(function($q) {
            $q->whereNull('kelas')->orWhere('kelas', '');
        })->count();

        $this->info("Total siswa tanpa kelas: {$totalWithoutKelas}");
        $this->info("Batch size: {$batchSize}");
        $this->info("Delay antar API call: {$delay}ms");
        $this->newLine();

        if ($totalWithoutKelas === 0) {
            $this->info('Semua siswa sudah memiliki kelas. Tidak ada yang perlu di-backfill.');
            return self::SUCCESS;
        }

        if (!$this->confirm("Apakah Anda ingin melanjutkan backfill untuk {$totalWithoutKelas} siswa?")) {
            $this->warn('Dibatalkan oleh user.');
            return self::FAILURE;
        }

        $this->newLine();

        $stats = [
            'processed' => 0,
            'updated' => 0,
            'not_found' => 0,
            'errors' => 0,
            'skipped' => 0,
        ];

        $failedStudents = [];

        // Process in chunks
        User::whereHas('roles', function($q) {
            $q->where('name', 'siswa');
        })->where(function($q) {
            $q->whereNull('kelas')->orWhere('kelas', '');
        })->chunkById($batchSize, function($students) use (&$stats, $delay, $isDryRun, &$failedStudents) {
            $this->info("Processing batch of {$students->count()} students...");

            foreach ($students as $student) {
                $stats['processed']++;

                try {
                    // Fetch student data from SiPintu by NIS
                    $result = $this->sipintu->getStudents(nis: $student->nis, forceRefresh: true);

                    if ($result['success'] && !empty($result['data'])) {
                        $sipintuStudent = $result['data'][0] ?? null;

                        if ($sipintuStudent) {
                            $kelasFromSipintu = $sipintuStudent['kelas'] ?? $sipintuStudent['rombel'] ?? null;

                            if ($kelasFromSipintu) {
                                if (!$isDryRun) {
                                    $student->kelas = $kelasFromSipintu;
                                    $student->saveQuietly();
                                }
                                $stats['updated']++;
                                $this->line("  <info>✓</info> NIS {$student->nis}: {$student->name} → {$kelasFromSipintu}");
                            } else {
                                $stats['not_found']++;
                                $this->line("  <comment>⊘</comment> NIS {$student->nis}: {$student->name} (kelas tidak ada di SiPintu)");
                                $failedStudents[] = [
                                    'nis' => $student->nis,
                                    'name' => $student->name,
                                    'reason' => 'Kelas tidak ada di data SiPintu',
                                ];
                            }
                        } else {
                            $stats['not_found']++;
                            $this->line("  <comment>⊘</comment> NIS {$student->nis}: {$student->name} (data kosong dari SiPintu)");
                            $failedStudents[] = [
                                'nis' => $student->nis,
                                'name' => $student->name,
                                'reason' => 'Data kosong dari SiPintu',
                            ];
                        }
                    } else {
                        $stats['not_found']++;
                        $errorMsg = $result['error'] ?? 'Unknown error';
                        $this->line("  <comment>⊘</comment> NIS {$student->nis}: {$student->name} ({$errorMsg})");
                        $failedStudents[] = [
                            'nis' => $student->nis,
                            'name' => $student->name,
                            'reason' => $errorMsg,
                        ];
                    }

                    // Rate limiting delay
                    if ($delay > 0) {
                        usleep($delay * 1000);
                    }

                } catch (\Exception $e) {
                    $stats['errors']++;
                    $this->line("  <error>✗</error> NIS {$student->nis}: {$student->name} (Exception: {$e->getMessage()})");
                    $failedStudents[] = [
                        'nis' => $student->nis,
                        'name' => $student->name,
                        'reason' => 'Exception: ' . $e->getMessage(),
                    ];
                    Log::error('Backfill kelas error', [
                        'nis' => $student->nis,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $this->newLine();
        });

        // Summary
        $this->info('================================================================');
        $this->info('   BACKFILL SELESAI');
        $this->info('================================================================');
        $this->info("Total diproses: {$stats['processed']}");
        $this->info("Berhasil diupdate: {$stats['updated']}");
        $this->info("Tidak ditemukan di SiPintu: {$stats['not_found']}");
        $this->info("Error: {$stats['errors']}");
        $this->info("Dilewati: {$stats['skipped']}");

        if (!empty($failedStudents)) {
            $this->newLine();
            $this->warn('Siswa yang gagal/ tidak ditemukan:');
            foreach ($failedStudents as $failed) {
                $this->line("  - NIS: {$failed['nis']} | Name: {$failed['name']} | Reason: {$failed['reason']}");
            }
            $this->newLine();
            $this->warn('Silakan cek manual siswa-siswa tersebut di database atau SiPintu.');
        }

        // Log summary
        Log::info('Backfill kelas siswa completed', $stats);

        return self::SUCCESS;
    }
}
