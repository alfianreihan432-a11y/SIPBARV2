<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
                            {--no-backup : Lewati pembuatan backup tabel users}
                            {--force : Lewati pertanyaan konfirmasi sebelum menjalankan reset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset password massal pengguna ke default baru "password" khusus role Siswa dan Guru';

    /**
     * Protected roles that must NEVER be reset by this command.
     */
    protected array $protectedRoles = ['superadmin', 'admin', 'kepala_jurusan', 'petugas'];

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
        $this->line("Target Role            : <comment>{$role}</comment>");
        $this->line("Password Default Baru  : <info>password</info>");
        $this->line("Role Dilindungi        : <comment>" . implode(', ', $this->protectedRoles) . "</comment>");
        $this->newLine();

        // 1. Backup users table before proceeding unless --no-backup is set
        if (!$this->option('no-backup')) {
            try {
                $backupTableName = 'users_backup_' . date('Ymd_His');
                DB::statement("CREATE TABLE `{$backupTableName}` LIKE `users`");
                DB::statement("INSERT INTO `{$backupTableName}` SELECT * FROM `users`");
                $this->info("✓ Tabel users berhasil di-backup ke: <comment>{$backupTableName}</comment>");
                $this->newLine();
            } catch (\Exception $e) {
                $this->error("Gagal membuat backup tabel users: " . $e->getMessage());
                if (!$this->confirm('Apakah Anda ingin tetap melanjutkan proses TANPA backup?', false)) {
                    $this->warn('Operasi reset password dibatalkan.');
                    return Command::FAILURE;
                }
            }
        }

        // Konfirmasi jika tidak ada flag --force
        if (!$this->option('force')) {
            if (!$this->confirm('Apakah Anda yakin ingin me-reset password semua akun Siswa dan Guru ke "password"?', false)) {
                $this->warn('Operasi reset password dibatalkan.');
                return Command::SUCCESS;
            }
        }

        $newPasswordHash = Hash::make('password');
        $guruCount = 0;
        $siswaCount = 0;

        // 2. Query Guru (role 'guru' atau memiliki NIP, tapi BUKAN role dilindungi)
        if ($role === 'guru' || $role === 'all') {
            $guruUsers = User::where(function ($q) {
                $q->whereHas('roles', function ($rq) {
                    $rq->where('name', 'guru');
                })->orWhere(function ($nq) {
                    $nq->whereNotNull('nip')->where('nip', '!=', '');
                });
            })->whereDoesntHave('roles', function ($rq) {
                $rq->whereIn('name', $this->protectedRoles);
            })->get();

            $totalGuru = $guruUsers->count();

            if ($totalGuru > 0) {
                $this->info("Memproses {$totalGuru} akun Guru...");

                $this->withProgressBar($guruUsers, function (User $user) use ($newPasswordHash, &$guruCount) {
                    $user->password = $newPasswordHash;
                    $user->save();
                    $guruCount++;
                });

                $this->newLine(2);
            } else {
                $this->warn('Tidak ditemukan data user Guru.');
            }
        }

        // 3. Query Siswa (role 'siswa' atau memiliki NIS, tapi BUKAN role dilindungi)
        if ($role === 'siswa' || $role === 'all') {
            $siswaUsers = User::where(function ($q) {
                $q->whereHas('roles', function ($rq) {
                    $rq->where('name', 'siswa');
                })->orWhere(function ($nq) {
                    $nq->whereNotNull('nis')->where('nis', '!=', '');
                });
            })->whereDoesntHave('roles', function ($rq) {
                $rq->whereIn('name', $this->protectedRoles);
            })->get();

            $totalSiswa = $siswaUsers->count();

            if ($totalSiswa > 0) {
                $this->info("Memproses {$totalSiswa} akun Siswa...");

                $this->withProgressBar($siswaUsers, function (User $user) use ($newPasswordHash, &$siswaCount) {
                    $user->password = $newPasswordHash;
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
                ['Guru', 'password', $guruCount],
                ['Siswa', 'password', $siswaCount],
                ['TOTAL', 'password', $guruCount + $siswaCount],
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
