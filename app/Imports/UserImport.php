<?php

namespace App\Imports;

use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class UserImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows, WithBatchInserts, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // 1. Tentukan NIP atau NIS dari kolom Excel
        // Mendukung format kolom 'nip', 'nis', 'nip_nis', atau 'nipnis'
        $nip = null;
        $nis = null;

        if (!empty($row['nip'])) {
            $nip = trim((string) $row['nip']);
        } elseif (!empty($row['nis'])) {
            $nis = trim((string) $row['nis']);
        } elseif (!empty($row['nip_nis']) || !empty($row['nipnis'])) {
            $identifier = trim((string) ($row['nip_nis'] ?? $row['nipnis']));
            // Jika kolom 'kelas' terisi -> Siswa, jika 'jabatan' terisi atau tanpa kelas -> Guru
            if (!empty($row['kelas'])) {
                $nis = $identifier;
            } else {
                $nip = $identifier;
            }
        }

        // Jika tidak ada NIP maupun NIS, baris dilewati
        if (!$nip && !$nis) {
            return null;
        }

        // 2. Parsing format tanggal lahir (mendukung serial Excel & format string tanggal)
        $tanggalLahir = null;
        if (!empty($row['tanggal_lahir'])) {
            try {
                if (is_numeric($row['tanggal_lahir'])) {
                    $tanggalLahir = Carbon::instance(ExcelDate::excelToDateTimeObject($row['tanggal_lahir']))->format('Y-m-d');
                } else {
                    $tanggalLahir = Carbon::parse($row['tanggal_lahir'])->format('Y-m-d');
                }
            } catch (\Exception $e) {
                $tanggalLahir = null;
            }
        }

        // 3. Generate Email Otomatis: Guru (DDMMYYYY@sipbar.sch.id), Siswa ({nis}@sipbar.sch.id)
        if ($nip) {
            $email = User::generateTeacherEmail($nip, $tanggalLahir);
        } else {
            $email = "{$nis}@sipbar.sch.id";
        }

        // 4. Instansiasi Model User
        // Catatan: Kolom 'password' SENGAJA TIDAK diisi di sini agar event `creating` 
        // pada User model secara otomatis men-set default password (guru123 / siswa123).
        $user = new User([
            'name'              => trim((string) $row['name']),
            'email'             => $email,
            'nip'               => $nip,
            'nis'               => $nis,
            'kelas'             => !empty($row['kelas']) ? trim((string) $row['kelas']) : null,
            'jurusan'           => !empty($row['jurusan']) ? trim((string) $row['jurusan']) : null,
            'jabatan'           => !empty($row['jabatan']) ? trim((string) $row['jabatan']) : null,
            'phone'             => !empty($row['phone']) ? trim((string) $row['phone']) : (!empty($row['no_hp']) ? trim((string) $row['no_hp']) : null),
            'tanggal_lahir'     => $tanggalLahir,
            'alamat'            => !empty($row['alamat']) ? trim((string) $row['alamat']) : null,
            'email_verified_at' => now(),
        ]);

        return $user;
    }

    /**
     * Aturan validasi untuk setiap baris di Excel
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Ukuran batch saat insert database
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * Ukuran chunk saat membaca file Excel besar
     */
    public function chunkSize(): int
    {
        return 100;
    }
}
