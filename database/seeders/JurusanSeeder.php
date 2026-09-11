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
            ['nama' => 'PPLG', 'kode' => 'PPLG'],
            ['nama' => 'AKL', 'kode' => 'AKL'],
            ['nama' => 'PM', 'kode' => 'PM'],
            ['nama' => 'MPLB', 'kode' => 'MPLB'],
            ['nama' => 'TO', 'kode' => 'TO'],
        ];

        foreach ($jurusans as $jurusan) {
            \App\Models\Jurusan::create($jurusan);
        }
    }
}
