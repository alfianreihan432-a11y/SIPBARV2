<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Pastikan semua role ada
        $roles = ['super-admin', 'admin', 'petugas', 'guru', 'siswa'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@sipbar.sch.id'],
            [
                'name'               => 'Super Admin',
                'password'           => bcrypt('admin123'),
                'email_verified_at'  => now(),
            ]
        );
        $admin->syncRoles(['super-admin', 'admin']);

        // Guru
        $guru = User::updateOrCreate(
            ['email' => 'guru@sipbar.sch.id'],
            [
                'name'               => 'Budi Santoso',
                'password'           => bcrypt('guru123'),
                'email_verified_at'  => now(),
            ]
        );
        $guru->syncRoles(['guru']);

        // Siswa
        $siswa = User::updateOrCreate(
            ['email' => 'siswa@sipbar.sch.id'],
            [
                'name'               => 'Ahmad Fauzi',
                'password'           => bcrypt('siswa123'),
                'email_verified_at'  => now(),
            ]
        );
        $siswa->syncRoles(['siswa']);

        $this->command->info('Users seeded: admin@sipbar.sch.id, guru@sipbar.sch.id, siswa@sipbar.sch.id');
    }
}
