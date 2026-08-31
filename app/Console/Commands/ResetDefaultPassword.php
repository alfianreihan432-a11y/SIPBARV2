<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ResetDefaultPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sipbar:reset-default-password 
                            {--role=all : Filter target reset: "guru", "siswa", atau "all"}
                            {--force : Lewati pertanyaan konfirmasi sebelum menjalankan reset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset password massal pengguna ke default (guru: guru123, siswa: siswa123)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $role = strtolower((string) $this->option('role'));

        if (!in_array($role, ['guru', 'siswa', 'all'])) {
            $this->error("Pilihan role tidak valid. Gunakan 'guru', 'siswa', atau 'all'.");
            return Command::FAILURE;
        }

        $this->info('=====================================================');
        $this->info('   SIPBAR - Mass Reset Default Password Tool');
        $this->info('=====================================================');
        $this->line("Target Role: <comment>{$role}</comment>");
        $this->line("Password Default Guru  : <info>guru123</info>");
        $this->line("Password Default Siswa : <info>siswa123</info>");
        $this->newLine();

        // Konfirmasi jika tidak ada flag --force
        if (!$this->option('force')) {
            if (!$this->confirm('Apakah Anda yakin ingin me-reset password semua user sesuai target role di atas?', false)) {
                $this->warn('Operasi reset password dibatalkan.');
                return Command::SUCCESS;
            }
        }

        $guruCount = 0;
        $siswaCount = 0;

        // 1. Reset Password Guru (NIP terisi dan bukan null/kosong)
        if ($role === 'guru' || $role === 'all') {
            $guruUsers = User::whereNotNull('nip')->where('nip', '!=', '')->get();
            $totalGuru = $guruUsers->count();

            if ($totalGuru > 0) {
                $this->info("Memproses {$totalGuru} akun Guru...");
                $guruPassword = Hash::make('guru123');

                $this->withProgressBar($guruUsers, function (User $user) use ($guruPassword, &$guruCount) {
                    $user->password = $guruPassword;
                    $user->save();
                    $guruCount++;
                });

                $this->newLine(2);
            } else {
                $this->warn('Tidak ditemukan data user Guru.');
            }
        }

        // 2. Reset Password Siswa (NIS terisi dan bukan null/kosong)
        if ($role === 'siswa' || $role === 'all') {
            $siswaUsers = User::whereNotNull('nis')->where('nis', '!=', '')->get();
            $totalSiswa = $siswaUsers->count();

            if ($totalSiswa > 0) {
                $this->info("Memproses {$totalSiswa} akun Siswa...");
                $siswaPassword = Hash::make('siswa123');

                $this->withProgressBar($siswaUsers, function (User $user) use ($siswaPassword, &$siswaCount) {
                    $user->password = $siswaPassword;
                    $user->save();
                    $siswaCount++;
                });

                $this->newLine(2);
            } else {
                $this->warn('Tidak ditemukan data user Siswa.');
            }
        }

        // Summary Hasil
        $this->info('✓ Proses reset default password selesai!');
        $this->table(
            ['Kategori Role', 'Password Baru', 'Jumlah User Di-reset'],
            [
                ['Guru (NIP)', 'guru123', $guruCount],
                ['Siswa (NIS)', 'siswa123', $siswaCount],
                ['TOTAL', '-', $guruCount + $siswaCount],
            ]
        );

        Log::info('Mass reset default password executed', [
            'role_filter' => $role,
            'guru_count' => $guruCount,
            'siswa_count' => $siswaCount,
        ]);

        return Command::SUCCESS;
    }
}
