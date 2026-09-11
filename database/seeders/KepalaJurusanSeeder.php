<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KepalaJurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create kepala_jurusan role if not exists
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'kepala_jurusan']);

        $jurusans = \App\Models\Jurusan::all();
        $kepalaJurusanData = [
            [
                'email' => 'pplg@smkn1bangsri.sch.id',
                'password' => 'pplg123',
                'name' => 'Kepala Jurusan PPLG',
                'jurusan_kode' => 'PPLG',
            ],
            [
                'email' => 'akl@smkn1bangsri.sch.id',
                'password' => 'akl123',
                'name' => 'Kepala Jurusan AKL',
                'jurusan_kode' => 'AKL',
            ],
            [
                'email' => 'pm@smkn1bangsri.sch.id',
                'password' => 'pm123',
                'name' => 'Kepala Jurusan PM',
                'jurusan_kode' => 'PM',
            ],
            [
                'email' => 'mplb@smkn1bangsri.sch.id',
                'password' => 'mplb123',
                'name' => 'Kepala Jurusan MPLB',
                'jurusan_kode' => 'MPLB',
            ],
            [
                'email' => 'to@smkn1bangsri.sch.id',
                'password' => 'to123',
                'name' => 'Kepala Jurusan TO',
                'jurusan_kode' => 'TO',
            ],
        ];

        foreach ($kepalaJurusanData as $data) {
            $jurusan = $jurusans->where('kode', $data['jurusan_kode'])->first();

            if ($jurusan) {
                $kepalaJurusan = \App\Models\User::firstOrCreate(
                    ['email' => $data['email']],
                    [
                        'name' => $data['name'],
                        'password' => bcrypt($data['password']),
                        'email_verified_at' => now(),
                        'jurusan_id' => $jurusan->id,
                        'nip' => 'KAJUR-' . $data['jurusan_kode'],
                    ]
                );

                $kepalaJurusan->assignRole('kepala_jurusan');

                $this->command->info("Kepala Jurusan created: {$data['email']} / {$data['password']}");
            }
        }
    }
}
