<?php

namespace App\Console\Commands;

use App\Imports\KibbImport;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Console\Command;

class FixLegacyKibbImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kibb:fix-legacy-data {--dry-run : Preview changes without saving to database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse and split legacy combined description text into dedicated columns (nomor_registrasi, ukuran, bahan, asal_usul, dll)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');

        $this->info('=== SIPBAR: Perbaikan Data Barang KIBB Legacy ===');
        if ($isDryRun) {
            $this->warn('[DRY-RUN MODE] Perubahan hanya dipratinjau dan tidak akan disimpan ke database.');
        }

        // Retrieve items with concatenated descriptions
        $items = Item::where(function ($q) {
            $q->where('description', 'like', '%Kode KIBB:%')
              ->orWhere('description', 'like', '%No. Reg:%')
              ->orWhere('description', 'like', '%Bahan:%')
              ->orWhere('description', 'like', '%Asal Usul:%');
        })->get();

        if ($items->isEmpty()) {
            $this->info('Tidak ditemukan data barang dengan format deskripsi gabungan lama.');
            return Command::SUCCESS;
        }

        $this->info("Ditemukan {$items->count()} data barang yang perlu diperbaiki.\n");

        $locations = Location::all()->mapWithKeys(function ($loc) {
            $parts = array_filter([$loc->building, $loc->room ? "R. {$loc->room}" : null, $loc->floor ? "Lt. {$loc->floor}" : null]);
            return [$loc->id => implode(' - ', $parts)];
        })->toArray();

        $successCount = 0;
        $skippedCount = 0;
        $tableRows = [];

        foreach ($items as $item) {
            $rawDesc = $item->description;

            // Extract labels using Regex
            $extracted = $this->parseLegacyDescription($rawDesc);

            if (empty($extracted)) {
                $skippedCount++;
                continue;
            }

            $beforeDesc = $item->description;

            // Mapped values
            $kodeKibb   = $extracted['kode_kibb'] ?? $item->kode_kibb;
            $nomorReg   = $extracted['no_reg'] ?? ($item->nomor_reg ?? $item->nomor_registrasi);
            $ukuranRaw  = $extracted['ukuran'] ?? null;
            $ukuranEnum = KibbImport::normalizeUkuran($ukuranRaw) ?? $item->ukuran;
            $bahan      = $extracted['bahan'] ?? $item->bahan;
            $asalUsul   = $extracted['asal_usul'] ?? $item->asal_usul;
            $keterangan = $extracted['keterangan'] ?? null;
            $warna      = $extracted['warna'] ?? null;
            $noPabrik   = $extracted['no_pabrik'] ?? null;
            $noRangka   = $extracted['no_rangka'] ?? null;
            $noMesin    = $extracted['no_mesin'] ?? null;
            $noPolisi   = $extracted['no_polisi'] ?? null;
            $noBpkb     = $extracted['no_bpkb'] ?? null;

            // Location matching if null
            $locationId = $item->location_id;
            if (!$locationId && !empty($keterangan)) {
                $keteranganLower = strtolower($keterangan);
                foreach ($locations as $locId => $locName) {
                    $locLower = strtolower($locName);
                    if (str_contains($keteranganLower, $locLower) || str_contains($locLower, $keteranganLower)) {
                        $locationId = $locId;
                        break;
                    }
                    $words = preg_split('/[\s\-_,\.\/]+/', $locLower);
                    foreach ($words as $w) {
                        if (strlen($w) >= 3 && str_contains($keteranganLower, $w)) {
                            $locationId = $locId;
                            break 2;
                        }
                    }
                }
            }

            // Build clean description
            $cleanDescParts = [];
            if (!empty($keterangan)) {
                $cleanDescParts[] = $keterangan;
            }
            if (!empty($warna)) {
                $cleanDescParts[] = "Warna: {$warna}";
            }
            if (!empty($noPabrik) && $noPabrik !== '-') {
                $cleanDescParts[] = "No. Pabrik: {$noPabrik}";
            }
            if (!empty($noRangka) && $noRangka !== '-') {
                $cleanDescParts[] = "No. Rangka: {$noRangka}";
            }
            if (!empty($noMesin) && $noMesin !== '-') {
                $cleanDescParts[] = "No. Mesin: {$noMesin}";
            }
            if (!empty($noPolisi) && $noPolisi !== '-') {
                $cleanDescParts[] = "No. Polisi: {$noPolisi}";
            }
            if (!empty($noBpkb) && $noBpkb !== '-') {
                $cleanDescParts[] = "No. BPKB: {$noBpkb}";
            }
            if (!empty($ukuranRaw) && !$ukuranEnum && $ukuranRaw !== '-') {
                $cleanDescParts[] = "Spesifikasi Ukuran: {$ukuranRaw}";
            }

            $newDescription = implode(' | ', $cleanDescParts);

            // Update item fields
            $item->kode_kibb        = $kodeKibb ?: null;
            $item->nomor_reg        = $nomorReg ?: null;
            $item->nomor_registrasi = $nomorReg ?: null;
            $item->ukuran           = $ukuranEnum;
            $item->bahan            = $bahan ?: null;
            $item->asal_usul        = $asalUsul ?: null;
            $item->tahun_pembelian  = $item->tahun_pembelian ?: $item->purchase_year;
            $item->purchase_year    = $item->purchase_year ?: $item->tahun_pembelian;
            $item->harga            = $item->harga ?: $item->price;
            $item->price            = $item->price ?: $item->harga;
            $item->location_id      = $locationId;
            $item->description      = $newDescription;

            if (!$isDryRun) {
                $item->save();
            }

            $successCount++;

            if ($successCount <= 10) {
                $tableRows[] = [
                    $item->id,
                    \Illuminate\Support\Str::limit($item->name, 25),
                    $item->nomor_registrasi ?? '-',
                    $item->ukuran ?? '-',
                    $item->bahan ?? '-',
                    $item->asal_usul ?? '-',
                    \Illuminate\Support\Str::limit($newDescription, 30),
                ];
            }
        }

        $this->table(
            ['ID', 'Nama Barang', 'No. Reg', 'Ukuran', 'Bahan', 'Asal Usul', 'Deskripsi Baru'],
            $tableRows
        );

        if ($successCount > 10) {
            $this->line("... dan " . ($successCount - 10) . " data lainnya.");
        }

        $this->newLine();
        $this->info("✓ Berhasil memproses: {$successCount} data barang.");
        if ($skippedCount > 0) {
            $this->warn("! Dilewati (format tidak cocok): {$skippedCount} data.");
        }

        if ($isDryRun) {
            $this->warn("\nCatatan: Jalankan tanpa '--dry-run' untuk menyimpan perubahan secara permanen.");
        } else {
            $this->info("\nPerubahan telah disimpan ke database.");
        }

        return Command::SUCCESS;
    }

    protected function parseLegacyDescription(string $desc): array
    {
        $patterns = [
            'kode_kibb'  => '/(?:Kode\s*KIBB|Kode):\s*([^|]+)/i',
            'no_reg'     => '/(?:No\.\s*Reg|Nomor\s*Reg|Reg):\s*([^|]+)/i',
            'ukuran'     => '/(?:Ukuran|Ukuran\/CC|CC):\s*([^|]+)/i',
            'bahan'      => '/(?:Bahan):\s*([^|]+)/i',
            'warna'      => '/(?:Warna):\s*([^|]+)/i',
            'no_pabrik'  => '/(?:No\.\s*Pabrik|Nomor\s*Pabrik):\s*([^|]+)/i',
            'no_angka'   => '/(?:No\.\s*Rangka|Nomor\s*Rangka):\s*([^|]+)/i',
            'no_mesin'   => '/(?:No\.\s*Mesin|Nomor\s*Mesin):\s*([^|]+)/i',
            'no_polisi'  => '/(?:No\.\s*Polisi|Nomor\s*Polisi):\s*([^|]+)/i',
            'no_bpkb'    => '/(?:No\.\s*BPKB|Nomor\s*BPKB):\s*([^|]+)/i',
            'asal_usul'  => '/(?:Asal\s*Usul|Asal):\s*([^|]+)/i',
            'keterangan' => '/(?:Keterangan|Ket):\s*([^|]+)/i',
        ];

        $result = [];

        foreach ($patterns as $key => $pattern) {
            if (preg_match($pattern, $desc, $matches)) {
                $val = trim($matches[1]);
                if ($val !== '' && $val !== '-') {
                    $result[$key] = $val;
                }
            }
        }

        return $result;
    }
}
