<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ResetStudentTeacherDefaultPasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $protectedRoles = ['superadmin', 'admin', 'kepala_jurusan', 'petugas'];

        // 1. Backup users table
        try {
            $backupTableName = 'users_backup_' . date('Ymd_His');
            DB::statement("CREATE TABLE `{$backupTableName}` LIKE `users`");
            DB::statement("INSERT INTO `{$backupTableName}` SELECT * FROM `users`");
            $this->command->info("✓ Tabel users berhasil di-backup ke: {$backupTableName}");
        } catch (\Exception $e) {
            $this->command->warn("Gagal membuat backup tabel: " . $e->getMessage());
        }

        $newPasswordHash = Hash::make('password');

        // 2. Query all Guru
        $guruUsers = User::where(function ($q) {
            $q->whereHas('roles', function ($rq) {
                $rq->where('name', 'guru');
            })->orWhere(function ($nq) {
                $nq->whereNotNull('nip')->where('nip', '!=', '');
            });
        })->whereDoesntHave('roles', function ($rq) use ($protectedRoles) {
            $rq->whereIn('name', $protectedRoles);
        })->get();

        $guruCount = 0;
        foreach ($guruUsers as $guru) {
            $guru->password = $newPasswordHash;
            $guru->save();
            $guruCount++;
        }

        // 3. Query all Siswa
        $siswaUsers = User::where(function ($q) {
            $q->whereHas('roles', function ($rq) {
                $rq->where('name', 'siswa');
            })->orWhere(function ($nq) {
                $nq->whereNotNull('nis')->where('nis', '!=', '');
            });
        })->whereDoesntHave('roles', function ($rq) use ($protectedRoles) {
            $rq->whereIn('name', $protectedRoles);
        })->get();

        $siswaCount = 0;
        foreach ($siswaUsers as $siswa) {
            $siswa->password = $newPasswordHash;
            $siswa->save();
            $siswaCount++;
        }

        $this->command->info("✓ Berhasil me-reset password {$guruCount} akun Guru dan {$siswaCount} akun Siswa ke 'password'.");
        Log::info('ResetStudentTeacherDefaultPasswordSeeder executed', [
            'guru_count' => $guruCount,
            'siswa_count' => $siswaCount,
        ]);
    }
}
