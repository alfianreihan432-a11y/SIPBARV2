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

    public function __construct()
    {
        $this->categories = Category::all()->pluck('name', 'id')->toArray();
        
        $this->locations = Location::all()->mapWithKeys(function ($loc) {
            $parts = array_filter([$loc->building, $loc->room ? "R. {$loc->room}" : null, $loc->floor ? "Lt. {$loc->floor}" : null]);
            return [$loc->id => implode(' - ', $parts)];
        })->toArray();
    }

    public function collection(Collection $collection): void
    {
        \Log::info('KIBB Import - Processing collection with ' . count($collection) . ' rows');

        if ($collection->isEmpty()) {
            throw new \Exception('File Excel tidak memiliki data.');
        }

        // 1. Find Header Row
        $headerRowIndex = null;
        $columnMap = [];

        foreach ($collection as $index => $row) {
            $rowValues = array_map(function ($val) {
                return strtolower(trim(preg_replace('/\r\n|\r|\n/', ' ', (string) $val)));
            }, is_array($row) ? $row : $row->toArray());

            $hasKode = false;
            $hasNama = false;

            foreach ($rowValues as $colIdx => $val) {
                if (stripos($val, 'kode barang') !== false || stripos($val, 'kode_barang') !== false || $val === 'kode') {
                    $hasKode = true;
                }
                if (stripos($val, 'jenis barang') !== false || stripos($val, 'nama barang') !== false || stripos($val, 'nama') !== false || stripos($val, 'uraian') !== false) {
                    $hasNama = true;
                }
            }

            if ($hasKode && $hasNama) {
                $headerRowIndex = $index;
                // Build dynamic column map based on header names
                foreach ($rowValues as $colIdx => $val) {
                    if (stripos($val, 'kode barang') !== false || stripos($val, 'kode_barang') !== false || $val === 'kode') {
                        $columnMap['kode_barang'] = $colIdx;
                    } elseif (stripos($val, 'jenis barang') !== false || stripos($val, 'nama barang') !== false || stripos($val, 'nama') !== false || stripos($val, 'uraian') !== false) {
                        $columnMap['nama_barang'] = $colIdx;
                    } elseif (stripos($val, 'reg') !== false || stripos($val, 'register') !== false) {
                        $columnMap['nomor_reg'] = $colIdx;
                    } elseif (stripos($val, 'merk') !== false || stripos($val, 'type') !== false || stripos($val, 'tipe') !== false) {
                        $columnMap['merk_type'] = $colIdx;
                    } elseif (stripos($val, 'ukuran') !== false || stripos($val, 'cc') !== false) {
                        $columnMap['ukuran'] = $colIdx;
                    } elseif (stripos($val, 'bahan') !== false) {
                        $columnMap['bahan'] = $colIdx;
                    } elseif (stripos($val, 'warna') !== false) {
                        $columnMap['warna'] = $colIdx;
                    } elseif (stripos($val, 'tahun') !== false) {
                        $columnMap['tahun'] = $colIdx;
                    } elseif (stripos($val, 'pabrik') !== false) {
                        $columnMap['no_pabrik'] = $colIdx;
                    } elseif (stripos($val, 'rangka') !== false) {
                        $columnMap['no_rangka'] = $colIdx;
                    } elseif (stripos($val, 'mesin') !== false) {
                        $columnMap['no_mesin'] = $colIdx;
                    } elseif (stripos($val, 'polisi') !== false) {
                        $columnMap['no_polisi'] = $colIdx;
                    } elseif (stripos($val, 'bpkb') !== false) {
                        $columnMap['no_bpkb'] = $colIdx;
                    } elseif (stripos($val, 'asal') !== false || stripos($val, 'perolehan') !== false) {
                        $columnMap['asal_usul'] = $colIdx;
                    } elseif (stripos($val, 'harga') !== false || stripos($val, 'nilai') !== false) {
                        $columnMap['harga'] = $colIdx;
                    } elseif (stripos($val, 'keterangan') !== false || stripos($val, 'ket') !== false || stripos($val, 'lokasi') !== false) {
                        $columnMap['keterangan'] = $colIdx;
                    }
                }
                break;
            }
        }

        // Fallback default column indexes if header not found by exact string
        if ($headerRowIndex === null) {
            $headerRowIndex = 0;
            $columnMap = [
                'kode_barang' => 1,
                'nama_barang' => 2,
                'nomor_reg'   => 3,
                'merk_type'   => 4,
                'ukuran'      => 5,
                'bahan'       => 6,
                'warna'       => 7,
                'tahun'       => 8,
                'no_pabrik'   => 9,
                'no_rangka'   => 10,
                'no_mesin'    => 11,
                'no_polisi'   => 12,
                'no_bpkb'     => 13,
                'asal_usul'   => 14,
                'harga'       => 15,
                'keterangan'  => 16,
            ];
        }

        // Set default column positions if any missing
        $defaultMap = [
            'kode_barang' => 1,
            'nama_barang' => 2,
            'nomor_reg'   => 3,
            'merk_type'   => 4,
            'ukuran'      => 5,
            'bahan'       => 6,
            'warna'       => 7,
            'tahun'       => 8,
            'no_pabrik'   => 9,
            'no_rangka'   => 10,
            'no_mesin'    => 11,
            'no_polisi'   => 12,
            'no_bpkb'     => 13,
            'asal_usul'   => 14,
            'harga'       => 15,
            'keterangan'  => 16,
        ];
        $columnMap = array_merge($defaultMap, $columnMap);

        // 2. Iterate through data rows
        $dataStartRow = $headerRowIndex + 1;
        $totalRows = count($collection);

        for ($i = $dataStartRow; $i < $totalRows; $i++) {
            $row = is_array($collection[$i]) ? $collection[$i] : $collection[$i]->toArray();
            $rowNumber = $i + 1;

            // Check if entire row is empty
            $nonEmptyCells = array_filter($row, fn($c) => trim((string)$c) !== '');
            if (empty($nonEmptyCells)) {
                continue;
            }

            $kodeBarang = trim((string) ($row[$columnMap['kode_barang']] ?? ''));
            $namaBarang = trim((string) ($row[$columnMap['nama_barang']] ?? ''));

            // Check if this is a subheader row or index row
            $subHeaderKeywords = ['pabrik', 'rangka', 'mesin', 'polisi', 'bpkb', 'bertengger', 'bertengggar', 'sub rincian'];
            $isSubHeader = false;
            foreach ($subHeaderKeywords as $kw) {
                if (stripos($kodeBarang, $kw) !== false || stripos($namaBarang, $kw) !== false) {
                    $isSubHeader = true;
                    break;
                }
            }
            if ($isSubHeader) {
                continue;
            }

            // Skip numbering row where columns are just 1, 2, 3...
            if (is_numeric($kodeBarang) && (int)$kodeBarang <= 20 && is_numeric($namaBarang) && (int)$namaBarang <= 20) {
                continue;
            }

            // Skip summary/footer rows
            $footerKeywords = ['jumlah', 'total', 'mengetahui', 'kepala sekolah', 'pengurus barang', 'nip.'];
            $isFooter = false;
            foreach ($footerKeywords as $kw) {
                if (stripos($kodeBarang, $kw) !== false || stripos($namaBarang, $kw) !== false) {
                    $isFooter = true;
                    break;
                }
            }
            if ($isFooter) {
                continue;
            }

            // If nama barang is empty, record error
            if (empty($namaBarang)) {
                if (empty($kodeBarang)) {
                    continue;
                }
                $this->errors[] = [
                    'row' => $rowNumber,
                    'reason' => 'Nama / Jenis Barang tidak boleh kosong.',
                ];
                continue;
            }

            // Check duplicate by KIBB code and register number if provided
            if (!empty($kodeBarang)) {
                $query = Item::where('kode_kibb', $kodeBarang);
                if (!empty($nomorReg)) {
                    $query->where(function ($q) use ($nomorReg) {
                        $q->where('nomor_reg', $nomorReg)
                          ->orWhere('nomor_registrasi', $nomorReg);
                    });
                }
                $existing = $query->first();

                if ($existing) {
                    $regText = !empty($nomorReg) ? " (Reg: {$nomorReg})" : '';
                    $this->skipped[] = [
                        'row' => $rowNumber,
                        'kode_kibb' => $kodeBarang,
                        'name' => $namaBarang,
                        'reason' => "Barang dengan Kode KIBB '{$kodeBarang}'{$regText} sudah ada di inventaris ({$existing->name}).",
                    ];
                    continue;
                }
            }

            // Parse valid item data
            $itemData = $this->parseRowData($row, $columnMap, $rowNumber);
            $this->previewData[] = $itemData;
        }
    }

    protected function parseRowData(array $row, array $map, int $rowNumber): array
    {
        $kodeBarang     = trim((string) ($row[$map['kode_barang']] ?? ''));
        $namaBarang     = trim((string) ($row[$map['nama_barang']] ?? ''));
        $nomorReg       = trim((string) ($row[$map['nomor_reg']] ?? ''));
        $merkType       = trim((string) ($row[$map['merk_type']] ?? ''));
        $ukuran         = trim((string) ($row[$map['ukuran']] ?? ''));
        $bahan          = trim((string) ($row[$map['bahan']] ?? ''));
        $warna          = trim((string) ($row[$map['warna']] ?? ''));
        $tahunPembelian = trim((string) ($row[$map['tahun']] ?? ''));
        $noPabrik       = trim((string) ($row[$map['no_pabrik']] ?? ''));
        $noRangka       = trim((string) ($row[$map['no_rangka']] ?? ''));
        $noMesin        = trim((string) ($row[$map['no_mesin']] ?? ''));
        $noPolisi       = trim((string) ($row[$map['no_polisi']] ?? ''));
        $noBpkb         = trim((string) ($row[$map['no_bpkb']] ?? ''));
        $asalUsul       = trim((string) ($row[$map['asal_usul']] ?? ''));
        $hargaRaw       = trim((string) ($row[$map['harga']] ?? ''));
        $keterangan     = trim((string) ($row[$map['keterangan']] ?? ''));

        // Parse Brand & Type
        $brand = null;
        $type = null;
        if (!empty($merkType)) {
            if (strpos($merkType, ' - ') !== false) {
                $parts = explode(' - ', $merkType, 2);
                $brand = trim($parts[0]);
                $type  = trim($parts[1] ?? '');
            } elseif (strpos($merkType, '/') !== false) {
                $parts = explode('/', $merkType, 2);
                $brand = trim($parts[0]);
                $type  = trim($parts[1] ?? '');
            } else {
                $brand = $merkType;
            }
        }

        // Parse Price
        $price = 0;
        if (!empty($hargaRaw)) {
            $cleanedPrice = preg_replace('/[^0-9]/', '', $hargaRaw);
            if (is_numeric($cleanedPrice)) {
                $price = (float) $cleanedPrice;
            }
        }

        // Parse Year
        $purchaseYear = null;
        if (!empty($tahunPembelian)) {
            if (preg_match('/\b(19\d{2}|20\d{2})\b/', $tahunPembelian, $yearMatches)) {
                $purchaseYear = (int) $yearMatches[1];
            }
        }

        // Match Category & Location
        $categoryId = $this->matchCategory($namaBarang);
        $locationId = $this->matchLocation($keterangan);

        // Normalize ukuran to dropdown options 'Kecil', 'Sedang', 'Besar'
        $ukuranEnum = $this->normalizeUkuran($ukuran);

        // Build clean description containing only extra notes & serial identifiers
        $descLines = [];
        if (!empty($keterangan)) {
            $descLines[] = $keterangan;
        }
        if (!empty($warna)) {
            $descLines[] = "Warna: {$warna}";
        }
        if (!empty($noPabrik) && $noPabrik !== '-') {
            $descLines[] = "No. Pabrik: {$noPabrik}";
        }
        if (!empty($noRangka) && $noRangka !== '-') {
            $descLines[] = "No. Rangka: {$noRangka}";
        }
        if (!empty($noMesin) && $noMesin !== '-') {
            $descLines[] = "No. Mesin: {$noMesin}";
        }
        if (!empty($noPolisi) && $noPolisi !== '-') {
            $descLines[] = "No. Polisi: {$noPolisi}";
        }
        if (!empty($noBpkb) && $noBpkb !== '-') {
            $descLines[] = "No. BPKB: {$noBpkb}";
        }
        if (!empty($ukuran) && !$ukuranEnum && $ukuran !== '-') {
            $descLines[] = "Spesifikasi Ukuran: {$ukuran}";
        }

        $description = implode(' | ', $descLines);

        return [
            'row'              => $rowNumber,
            'name'             => $namaBarang,
            'kode_kibb'        => $kodeBarang ?: null,
            'nomor_reg'        => $nomorReg ?: null,
            'nomor_registrasi' => $nomorReg ?: null,
            'brand'            => $brand ?: null,
            'type'             => $type ?: null,
            'ukuran'           => $ukuranEnum,
            'bahan'            => $bahan ?: null,
            'purchase_year'    => $purchaseYear,
            'tahun_pembelian'  => $purchaseYear,
            'price'            => $price,
            'harga'            => $price,
            'asal_usul'        => $asalUsul ?: null,
            'category_id'      => $categoryId,
            'location_id'      => $locationId,
            'description'      => $description,
            'stock'            => 1,
            'condition'        => 'Baik',
            'status'           => 'Tersedia',
        ];
    }

    public static function normalizeUkuran(?string $val): ?string
    {
        if (empty($val)) {
            return null;
        }

        $v = strtolower(trim($val));
        if ($v === 'kecil' || str_contains($v, 'kecil') || str_contains($v, 'small')) {
            return 'Kecil';
        }
        if ($v === 'sedang' || str_contains($v, 'sedang') || str_contains($v, 'medium')) {
            return 'Sedang';
        }
        if ($v === 'besar' || str_contains($v, 'besar') || str_contains($v, 'large')) {
            return 'Besar';
        }

        return null;
    }

    protected function matchCategory(string $name): ?int
    {
        if (empty($name)) {
            return null;
        }

        $nameLower = strtolower($name);

        foreach ($this->categories as $id => $catName) {
            $catLower = strtolower($catName);
            if (strpos($nameLower, $catLower) !== false || strpos($catLower, $nameLower) !== false) {
                return $id;
            }
        }

        $keywordMap = [
            'laptop'    => 'Laptop',
            'notebook'  => 'Laptop',
            'pc'        => 'elektronik',
            'komputer'  => 'elektronik',
            'printer'   => 'elektronik',
            'scanner'   => 'elektronik',
            'proyektor' => 'Proyektor',
            'projector' => 'Proyektor',
            'infocus'   => 'Proyektor',
            'meja'      => 'Meja',
            'kursi'     => 'Meja',
            'lemari'    => 'Meja',
        ];

        foreach ($keywordMap as $kw => $targetCat) {
            if (strpos($nameLower, $kw) !== false) {
                foreach ($this->categories as $id => $catName) {
                    if (strcasecmp($catName, $targetCat) === 0) {
                        return $id;
                    }
                }
            }
        }

        return null;
    }

    protected function matchLocation(string $text): ?int
    {
        if (empty($text)) {
            return null;
        }

        $textLower = strtolower($text);

        foreach ($this->locations as $id => $locName) {
            $locLower = strtolower($locName);
            if (strpos($textLower, $locLower) !== false || strpos($locLower, $textLower) !== false) {
                return $id;
            }

            $words = preg_split('/[\s\-_,\.\/]+/', $locLower);
            foreach ($words as $w) {
                if (strlen($w) >= 3 && strpos($textLower, $w) !== false) {
                    return $id;
                }
            }
        }

        return null;
    }

    public function getPreviewData(): array
    {
        return $this->previewData;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getSkipped(): array
    {
        return $this->skipped;
    }
}