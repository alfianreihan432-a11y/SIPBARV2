<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateEmailDomain extends Command
{
    protected $signature = 'sipbar:migrate-email-domain
                            {--force : Lewati pertanyaan konfirmasi sebelum menjalankan migrasi}';

    protected $description = 'Migrasi domain email dari @sipbar.sch.id ke @smkn1bangsri.sch.id untuk semua user yang sudah ada';

    public function handle(): int
    {
        $this->info('================================================================');
        $this->info('   SIPBAR - Migrasi Domain Email');
        $this->info('================================================================');
        $this->line('Domain Lama : <info>@sipbar.sch.id</info>');
        $this->line('Domain Baru : <info>@smkn1bangsri.sch.id</info>');
        $this->newLine();

        if (!$this->option('force')) {
            if (!$this->confirm('Apakah Anda yakin ingin melanjutkan migrasi email domain?')) {
                $this->warn('Migrasi dibatalkan.');
                return self::FAILURE;
            }
        }

        $users = User::where('email', 'like', '%@sipbar.sch.id')->get();
        $totalUsers = $users->count();

        if ($totalUsers === 0) {
            $this->info('Tidak ada user dengan domain @sipbar.sch.id yang ditemukan.');
            $this->info('Migrasi selesai.');
            return self::SUCCESS;
        }

        $this->info("Ditemukan {$totalUsers} user dengan domain @sipbar.sch.id.");
        $this->newLine();

        $updatedCount = 0;
        $failedCount = 0;

        $skippedCount = 0;
        $skippedUsers = [];

        DB::beginTransaction();

        try {
            foreach ($users as $user) {
                $oldEmail = $user->email;
                $newEmail = str_replace('@sipbar.sch.id', '@smkn1bangsri.sch.id', $oldEmail);

                if ($oldEmail === $newEmail) {
                    continue;
                }

                // Cek apakah email baru sudah dipakai oleh user lain
                $existingUser = User::where('email', $newEmail)->where('id', '!=', $user->id)->first();
                if ($existingUser) {
                    $skippedCount++;
                    $skippedUsers[] = [
                        'old' => $oldEmail,
                        'new' => $newEmail,
                        'existing_user_id' => $existingUser->id,
                    ];
                    $this->line("  <comment>⊘</comment> {$oldEmail} → {$newEmail} (email sudah dipakai user ID {$existingUser->id})");
                    continue;
                }

                $user->email = $newEmail;
                $user->saveQuietly();
                $updatedCount++;

                $this->line("  <info>✓</info> {$oldEmail} → {$newEmail}");
            }

            DB::commit();

            $this->newLine();
            $this->info('================================================================');
            $this->info('   MIGRASI SELESAI');
            $this->info('================================================================');
            $this->info("Total user dengan domain lama: {$totalUsers}");
            $this->info("Berhasil diupdate: {$updatedCount}");
            $this->info("Dilewati (email sudah ada): {$skippedCount}");

            if ($skippedCount > 0) {
                $this->newLine();
                $this->warn('User yang dilewati (email baru sudah dipakai):');
                foreach ($skippedUsers as $skipped) {
                    $this->line("  - {$skipped['old']} → {$skipped['new']} (sudah ada)");
                }
                $this->newLine();
                $this->warn('Silakan cek manual user-user tersebut di database.');
            }

            $this->newLine();
            $this->info('Domain email berhasil diubah dari @sipbar.sch.id ke @smkn1bangsri.sch.id');

            return self::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Terjadi kesalahan saat migrasi: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
