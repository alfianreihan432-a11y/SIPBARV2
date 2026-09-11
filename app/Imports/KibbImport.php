<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class KibbImport implements ToCollection
{
    protected $previewData = [];
    protected $errors = [];
    protected $skipped = [];
    protected $categories = [];
    protected $locations = [];
    protected $currentSheetName = '';

    public function __construct()
    {
        $this->categories = Category::all()->pluck('name', 'id')->toArray();
        $this->locations = Location::all()->pluck('name', 'id')->toArray();
    }

    public function collection(Collection $collection): void
    {
        // Log for verification
        \Log::info('KIBB Import - Processing collection with ' . count($collection) . ' rows');

        // Find header row with "Kode Barang" AND "Jenis Barang" to avoid empty merged cell rows
        $headerRow = null;
        $searchTerm1 = 'kode barang';
        $searchTerm2 = 'jenis barang';

        // Collect debug info for first 15 rows
        $headerDebugInfo = "First 15 rows (all cells):\n";

        foreach ($collection as $index => $row) {
            $hasKodeBarang = false;
            $hasJenisBarang = false;

            // Debug: collect cell values for first 15 rows
            if ($index < 15) {
                $cellValues = [];
                foreach ($row as $cell) {
                    $cellValue = (string) $cell;
                    $cellValue = trim($cellValue);
                    $cellValue = preg_replace('/\r\n|\r|\n/', ' ', $cellValue);
                    if (strlen($cellValue) > 0) {
                        $cellValues[] = "\"$cellValue\"";
                    }
                }
                $headerDebugInfo .= "Row $index: [" . implode(', ', $cellValues) . "]\n";
            }

            foreach ($row as $cell) {
                // Normalize cell value: cast to string, trim, replace line breaks
                $cellValue = (string) $cell;
                $cellValue = trim($cellValue);
                $cellValue = preg_replace('/\r\n|\r|\n/', ' ', $cellValue);
                $cellValueLower = strtolower($cellValue);

                if (stripos($cellValueLower, $searchTerm1) !== false) {
                    $hasKodeBarang = true;
                }
                if (stripos($cellValueLower, $searchTerm2) !== false) {
                    $hasJenisBarang = true;
                }

                if ($hasKodeBarang && $hasJenisBarang) {
                    $headerRow = $index;
                    break 2;
                }
            }
        }

        if ($headerRow === null) {
            throw new \Exception('Header "Kode Barang" dan "Jenis Barang" tidak ditemukan dalam file Excel. Pastikan sheet KIBB memiliki header tabel dengan kolom tersebut.' . "\n\n" . $headerDebugInfo);
        }

        \Log::info('KIBB Import - Header row found at: ' . $headerRow);
        error_log('KIBB Import - Header row found at: ' . $headerRow);

        // Find data start row using BMN code pattern matching
        $dataStartRow = null;
        for ($i = $headerRow + 1; $i < count($collection); $i++) {
            $row = $collection[$i];
            $kodeBarang = trim((string) ($row[1] ?? ''));
            $jenisBarang = trim((string) ($row[2] ?? ''));

            // Skip if either is empty
            if (empty($kodeBarang) || empty($jenisBarang)) {
                continue;
            }

            // Skip sub-header rows (Pabrik, Rangka, Mesin, Polisi, BPKB)
            $subHeaderKeywords = ['pabrik', 'rangka', 'mesin', 'polisi', 'bpkb'];
            $isSubHeader = false;
            foreach ($subHeaderKeywords as $keyword) {
                if (stripos(strtolower($kodeBarang), $keyword) !== false || 
                    stripos(strtolower($jenisBarang), $keyword) !== false) {
                    $isSubHeader = true;
                    break;
                }
            }
            if ($isSubHeader) {
                continue;
            }

            // Skip if kode barang is just a number (index row like 1, 2, 3, etc)
            if (is_numeric($kodeBarang) && strlen($kodeBarang) <= 3) {
                continue;
            }

            // Skip if jenis barang is just a number
            if (is_numeric($jenisBarang) && strlen($jenisBarang) <= 3) {
                continue;
            }

            // Check if kode barang matches BMN code pattern (contains dots and/or dashes, length > 10)
            // Example: "11.01.33.20.010101.00009.00314.2026-1.3.2.03.03.05.024"
            $hasDots = strpos($kodeBarang, '.') !== false;
            $hasDashes = strpos($kodeBarang, '-') !== false;
            $isBmnCode = ($hasDots || $hasDashes) && strlen($kodeBarang) > 10;

            // Check if this looks like data row
            // Criteria: BMN code pattern OR (code length > 5 AND name length > 3)
            if ($isBmnCode || (strlen($kodeBarang) > 5 && strlen($jenisBarang) > 3)) {
                // Ensure it's not the header row itself
                $headerText = trim((string) ($collection[$headerRow][1] ?? ''));
                if (strcasecmp($kodeBarang, $headerText) !== 0) {
                    $dataStartRow = $i;
                    break;
                }
            }
        }

        if ($dataStartRow === null) {
            // Enhanced debug: show 25 rows after headerRow to see more context
            $debugInfo = "Header row: $headerRow\n";
            $debugInfo .= "Next 25 rows (cols 1 & 2):\n";
            for ($i = $headerRow + 1, $count = 0; $i < count($collection) && $count < 25; $i++, $count++) {
                $row = $collection[$i];
                $col1 = trim((string) ($row[1] ?? ''));
                $col2 = trim((string) ($row[2] ?? ''));
                $hasDots = strpos($col1, '.') !== false;
                $hasDashes = strpos($col1, '-') !== false;
                $debugInfo .= "Row $i: col1=\"$col1\" (len=" . strlen($col1) . ", dots=" . ($hasDots ? 'yes' : 'no') . ", dashes=" . ($hasDashes ? 'yes' : 'no') . "), col2=\"$col2\" (len=" . strlen($col2) . ")\n";
            }
            $debugInfo .= "\nTotal rows in sheet: " . count($collection) . "\n";
            $debugInfo .= "Expected format: Kode Barang BMN (contains dots/dashes, length > 10, e.g., \"11.01.33.20.010101.00009.00314.2026-1.3.2.03.03.05.024\"), Jenis Barang (length > 3, text)\n";
            throw new \Exception('Data start row tidak ditemukan. Tidak ada baris dengan format kode KIBB yang valid.' . "\n\n" . $debugInfo);
        }

        \Log::info('KIBB Import - Data start row found at: ' . $dataStartRow);
        error_log('KIBB Import - Data start row found at: ' . $dataStartRow);

        // Parse data rows
        foreach ($collection as $index => $row) {
            if ($index < $dataStartRow) {
                continue;
            }

            // Stop if both Kode Barang and Jenis Barang are empty
            $kodeBarang = trim((string) ($row[1] ?? ''));
            $jenisBarang = trim((string) ($row[2] ?? ''));

            if (empty($kodeBarang) && empty($jenisBarang)) {
                break;
            }

            // Skip if name is empty
            if (empty($jenisBarang)) {
                $this->errors[] = [
                    'row' => $index + 1,
                    'reason' => 'Jenis Barang/Nama Barang kosong',
                    'data' => $row,
                ];
                continue;
            }

            // Check for duplicate kode_kibb
            if (!empty($kodeBarang)) {
                $existing = Item::where('kode_kibb', $kodeBarang)->first();
                if ($existing) {
                    $this->skipped[] = [
                        'row' => $index + 1,
                        'kode_kibb' => $kodeBarang,
                        'reason' => 'Duplikat kode_kibb',
                    ];
                    continue;
                }
            }

            // Parse row data
            $itemData = $this->parseRow($row, $index + 1);
            $this->previewData[] = $itemData;
        }
    }

    protected function parseRow($row, $rowNumber)
    {
        // Column mapping based on KIBB format
        // 0: No, 1: Kode Barang, 2: Jenis Barang/Nama Barang, 3: Reg., 4: Merk type,
        // 5: Ukuran/CC, 6: Bahan, 7: Warna, 8: Tahun Pembelian, 9: Nomor Pabrik, 10: Nomor Rangka,
        // 11: Nomor Mesin, 12: Nomor Polisi, 13: Nomor BPKB, 14: Asal Usul, 15: Harga, 16: Keterangan

        $kodeBarang = trim((string) ($row[1] ?? ''));
        $jenisBarang = trim((string) ($row[2] ?? ''));
        $nomorReg = trim((string) ($row[3] ?? ''));
        $merkType = trim((string) ($row[4] ?? ''));
        $ukuranCc = trim((string) ($row[5] ?? ''));
        $bahan = trim((string) ($row[6] ?? ''));
        $warna = trim((string) ($row[7] ?? ''));
        $tahunPembelian = trim((string) ($row[8] ?? ''));
        $nomorPabrik = trim((string) ($row[9] ?? ''));
        $nomorRangka = trim((string) ($row[10] ?? ''));
        $nomorMesin = trim((string) ($row[11] ?? ''));
        $nomorPolisi = trim((string) ($row[12] ?? ''));
        $nomorBpkb = trim((string) ($row[13] ?? ''));
        $asalUsul = trim((string) ($row[14] ?? ''));
        $harga = trim((string) ($row[15] ?? ''));
        $keterangan = trim((string) ($row[16] ?? ''));

        // Parse merk and tipe
        $merk = null;
        $tipe = null;
        if (!empty($merkType)) {
            if (strpos($merkType, ' - ') !== false) {
                $parts = explode(' - ', $merkType, 2);
                $merk = trim($parts[0]);
                $tipe = trim($parts[1] ?? null);
            } else {
                $merk = trim($merkType);
            }
        }

        // Build description
        $descriptionParts = [];
        if (!empty($ukuranCc)) {
            $descriptionParts[] = "Ukuran: {$ukuranCc}";
        }
        if (!empty($bahan)) {
            $descriptionParts[] = "Bahan: {$bahan}";
        }
        if (!empty($warna)) {
            $descriptionParts[] = "Warna: {$warna}";
        }

        // Add vehicle numbers if present
        $vehicleNumbers = [];
        if (!empty($nomorRangka)) {
            $vehicleNumbers[] = "No. Rangka: {$nomorRangka}";
        }
        if (!empty($nomorMesin)) {
            $vehicleNumbers[] = "No. Mesin: {$nomorMesin}";
        }
        if (!empty($nomorPolisi)) {
            $vehicleNumbers[] = "No. Polisi: {$nomorPolisi}";
        }
        if (!empty($nomorBpkb)) {
            $vehicleNumbers[] = "No. BPKB: {$nomorBpkb}";
        }
        if (!empty($nomorPabrik)) {
            $vehicleNumbers[] = "No. Pabrik: {$nomorPabrik}";
        }
        if (!empty($vehicleNumbers)) {
            $descriptionParts[] = implode(', ', $vehicleNumbers);
        }

        if (!empty($asalUsul)) {
            $descriptionParts[] = "Asal Usul: {$asalUsul}";
        }

        if (!empty($keterangan)) {
            $descriptionParts[] = "Keterangan: {$keterangan}";
        }

        $description = implode('. ', $descriptionParts);

        // Match category from name
        $categoryId = $this->matchCategory($jenisBarang);

        // Match location from keterangan
        $locationId = $this->matchLocation($keterangan);

        // Parse price
        $price = 0;
        if (!empty($harga)) {
            $price = (float) str_replace(['.', ','], '', $harga);
        }

        // Parse purchase year
        $purchaseYear = null;
        if (!empty($tahunPembelian)) {
            $purchaseYear = (int) $tahunPembelian;
        }

        return [
            'row' => $rowNumber,
            'kode_kibb' => $kodeBarang,
            'nomor_reg' => $nomorReg,
            'name' => $jenisBarang,
            'merk' => $merk,
            'tipe' => $tipe,
            'description' => $description,
            'purchase_year' => $purchaseYear,
            'price' => $price,
            'category_id' => $categoryId,
            'category_name' => $categoryId ? $this->categories[$categoryId] : 'Belum Dikategorikan',
            'location_id' => $locationId,
            'location_name' => $locationId ? $this->locations[$locationId] : null,
            'stock' => 1,
            'condition' => 'Baik',
            'status' => 'Tersedia',
            'needs_review' => !$categoryId || !$locationId,
        ];
    }

    protected function matchCategory($name)
    {
        if (empty($name)) {
            return null;
        }

        $nameLower = strtolower($name);

        // Try to find matching category by keyword
        foreach ($this->categories as $id => $categoryName) {
            $categoryLower = strtolower($categoryName);
            if (strpos($nameLower, $categoryLower) !== false || strpos($categoryLower, $nameLower) !== false) {
                return $id;
            }
        }

        // Try partial word matching
        $words = explode(' ', $nameLower);
        foreach ($this->categories as $id => $categoryName) {
            $categoryLower = strtolower($categoryName);
            foreach ($words as $word) {
                if (strlen($word) > 3 && strpos($categoryLower, $word) !== false) {
                    return $id;
                }
            }
        }

        return null;
    }

    protected function matchLocation($keterangan)
    {
        if (empty($keterangan)) {
            return null;
        }

        $keteranganLower = strtolower($keterangan);

        foreach ($this->locations as $id => $locationName) {
            $locationLower = strtolower($locationName);
            if (strpos($keteranganLower, $locationLower) !== false) {
                return $id;
            }
        }

        return null;
    }

    public function getPreviewData()
    {
        return $this->previewData;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSkipped()
    {
        return $this->skipped;
    }
}