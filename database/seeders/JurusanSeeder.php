<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusans = [
            ['nama' => 'Pengembangan Perangkat Lunak dan Gim (PPLG)', 'kode' => 'PPLG'],
            ['nama' => 'Akuntansi dan Keuangan Lembaga (AKL)',         'kode' => 'AKL'],
            ['nama' => 'Manajemen Perkantoran dan Layanan Bisnis (MPLB)', 'kode' => 'MPLB'],
            ['nama' => 'Teknik Otomotif (TO)',                           'kode' => 'TO'],
            ['nama' => 'Pemasaran (PM)',                                 'kode' => 'PM'],
        ];

        foreach ($jurusans as $data) {
            \App\Models\Jurusan::firstOrCreate(
                ['kode' => $data['kode']],
                ['nama' => $data['nama']]
            );
        }
    }
}
