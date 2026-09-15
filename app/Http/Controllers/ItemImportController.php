<?php

namespace App\Http\Controllers;

use App\Imports\KibbImport;
use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ItemImportController extends Controller
{
    /**
     * Download the official KIBB Excel Template
     */
    public function downloadTemplate(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('KIBB');

        // Headers
        $headers = [
            'A1' => 'No',
            'B1' => 'Kode Barang',
            'C1' => 'Jenis Barang / Nama Barang',
            'D1' => 'Nomor Register',
            'E1' => 'Merk / Type',
            'F1' => 'Ukuran / CC',
            'G1' => 'Bahan',
            'H1' => 'Warna',
            'I1' => 'Tahun Pembelian',
            'J1' => 'Nomor Pabrik',
            'K1' => 'Nomor Rangka',
            'L1' => 'Nomor Mesin',
            'M1' => 'Nomor Polisi',
            'N1' => 'Nomor BPKB',
            'O1' => 'Asal Usul',
            'P1' => 'Harga (Rp)',
            'Q1' => 'Keterangan',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Header Styling
        $headerStyle = [
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size'  => 11,
                'name'  => 'Segoe UI',
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1D4ED8'], // Blue-700
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => '93C5FD'],
                ],
            ],
        ];
        $sheet->getStyle('A1:Q1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(32);

        // Sample Data Rows
        $sampleData = [
            [
                1,
                '11.01.33.20.010101.00009.00314.2024-1.3.2.03.03.05.024',
                'Laptop Asus Vivobook 14',
                '0001',
                'ASUS - Vivobook 14 Core i5',
                '14 Inch',
                'Aluminium / Plastik',
                'Silver',
                2024,
                'PB-ASUS-99881',
                '-',
                '-',
                '-',
                '-',
                'Pembelian APBD',
                8500000,
                'Gedung A - R. R-201 (Lt. 2)',
            ],
            [
                2,
                '11.01.33.20.010101.00009.00315.2023-1.3.2.03.03.05.025',
                'Proyektor Epson EB-X500',
                '0002',
                'Epson - EB-X500 3600 Lumens',
                '30x23 cm',
                'Plastik Keras',
                'Putih',
                2023,
                'PB-EPS-44122',
                '-',
                '-',
                '-',
                '-',
                'Dana BOS',
                6200000,
                'Gedung A - R. R-201 (Lt. 2)',
            ],
            [
                3,
                '11.01.33.20.010101.00009.00316.2024-1.3.2.03.03.05.026',
                'Meja Guru Kayu Jati',
                '0003',
                'Kayu Jati Grade A',
                '120x60x75 cm',
                'Kayu Jati',
                'Coklat Natural',
                2024,
                '-',
                '-',
                '-',
                '-',
                '-',
                'Pembelian Sekolah',
                1500000,
                'Gedung A - R. R-201 (Lt. 2)',
            ],
            [
                4,
                '11.01.33.20.010101.00009.00317.2024-1.3.2.03.03.05.027',
                'Printer Canon PIXMA G2010',
                '0004',
                'Canon - PIXMA G2010 All-in-One',
                '44x33 cm',
                'Plastik ABS',
                'Hitam',
                2024,
                'PB-CAN-77112',
                '-',
                '-',
                '-',
                '-',
                'Dana BOS',
                2400000,
                'Gedung A - R. R-201 (Lt. 2)',
            ],
        ];

        $rowIdx = 2;
        foreach ($sampleData as $row) {
            $colLetter = 'A';
            foreach ($row as $val) {
                $sheet->setCellValue($colLetter . $rowIdx, $val);
                $colLetter++;
            }
            $sheet->getRowDimension($rowIdx)->setRowHeight(24);
            $rowIdx++;
        }

        // Data Rows Styling
        $dataStyle = [
            'font' => [
                'size' => 10,
                'name' => 'Segoe UI',
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'E2E8F0'],
                ],
            ],
        ];
        $sheet->getStyle('A2:Q' . ($rowIdx - 1))->applyFromArray($dataStyle);

        // Center align specific columns (No, Kode, Reg, Tahun)
        $sheet->getStyle('A2:A' . ($rowIdx - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D2:D' . ($rowIdx - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I2:I' . ($rowIdx - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('P2:P' . ($rowIdx - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Auto-fit column widths
        foreach (range('A', 'Q') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'template-import-kibb-' . date('Ymd') . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    /**
     * Upload & Process KIBB Excel File
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ], [
            'file.required' => 'File Excel wajib dipilih.',
            'file.mimes'    => 'File harus berformat Excel (.xlsx atau .xls).',
            'file.max'      => 'Ukuran file maksimal adalah 10MB.',
        ]);

        try {
            $uploadedFile = $request->file('file');
            $pathname = $uploadedFile->getPathname();

            // Load spreadsheet
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($pathname);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($pathname);

            // Find sheet named "KIBB" (case-insensitive & trimmed)
            $kibbSheetIndex = null;
            $sheetNames = $spreadsheet->getSheetNames();

            foreach ($sheetNames as $idx => $name) {
                if (strcasecmp(trim($name), 'KIBB') === 0) {
                    $kibbSheetIndex = $idx;
                    break;
                }
            }

            if ($kibbSheetIndex === null) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Sheet bernama "KIBB" tidak ditemukan dalam file Excel. Sheet yang terdeteksi: [' . implode(', ', $sheetNames) . ']. Silakan pastikan nama sheet adalah "KIBB" atau unduh Template Excel yang disediakan.',
                ], 422);
            }

            // Extract rows from KIBB sheet
            $worksheet = $spreadsheet->getSheet($kibbSheetIndex);
            $dataArray = $worksheet->toArray(null, true, false, false);
            $collection = new \Illuminate\Support\Collection($dataArray);

            $import = new KibbImport();
            $import->collection($collection);

            $previewData = $import->getPreviewData();
            $errors      = $import->getErrors();
            $skipped     = $import->getSkipped();

            if (empty($previewData) && empty($errors) && empty($skipped)) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Tidak ada data barang yang ditemukan pada sheet "KIBB". Pastikan baris data tidak kosong.',
                ], 422);
            }

            // Fallback default category
            $defaultCategory = Category::firstOrCreate(
                ['name' => 'Umum'],
                ['icon' => '📦', 'color' => '#2563eb', 'description' => 'Kategori umum']
            );

            // Insert items to DB
            $successCount = 0;
            $errorCount = 0;
            $errorMessages = [];

            foreach ($previewData as $itemData) {
                try {
                    $code = strtoupper('BRG-' . substr(md5(uniqid((string)mt_rand(), true)), 0, 6));
                    $inventoryNumber = $this->generateInventoryNumber();

                    Item::create([
                        'code'             => $code,
                        'inventory_number' => $inventoryNumber,
                        'name'             => $itemData['name'] ?? 'Tanpa Nama',
                        'kode_kibb'        => $itemData['kode_kibb'] ?? null,
                        'nomor_reg'        => $itemData['nomor_reg'] ?? null,
                        'nomor_registrasi' => $itemData['nomor_registrasi'] ?? ($itemData['nomor_reg'] ?? null),
                        'brand'            => $itemData['brand'] ?? null,
                        'type'             => $itemData['type'] ?? null,
                        'ukuran'           => $itemData['ukuran'] ?? null,
                        'bahan'            => $itemData['bahan'] ?? null,
                        'purchase_year'    => $itemData['purchase_year'] ?? null,
                        'tahun_pembelian'  => $itemData['tahun_pembelian'] ?? ($itemData['purchase_year'] ?? null),
                        'price'            => $itemData['price'] ?? 0,
                        'harga'            => $itemData['harga'] ?? ($itemData['price'] ?? 0),
                        'asal_usul'        => $itemData['asal_usul'] ?? null,
                        'condition'        => $itemData['condition'] ?? 'Baik',
                        'status'           => $itemData['status'] ?? 'Tersedia',
                        'stock'            => $itemData['stock'] ?? 1,
                        'description'      => $itemData['description'] ?? '',
                        'category_id'      => $itemData['category_id'] ?? $defaultCategory->id,
                        'location_id'      => $itemData['location_id'] ?? null,
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    $errorMessages[] = [
                        'row'    => $itemData['row'] ?? '-',
                        'reason' => 'Gagal menyimpan data barang (' . ($itemData['name'] ?? '-') . '): ' . $e->getMessage(),
                    ];
                }
            }

            $allErrors = array_merge($errors, $errorMessages);
            $isSuccess = $successCount > 0;

            $messageParts = [];
            if ($successCount > 0) {
                $messageParts[] = "{$successCount} barang berhasil diimpor.";
            }
            if (count($skipped) > 0) {
                $messageParts[] = count($skipped) . " barang dilewati (duplikat).";
            }
            if (count($allErrors) > 0) {
                $messageParts[] = count($allErrors) . " baris bermasalah / gagal.";
            }

            $message = !empty($messageParts) ? implode(' ', $messageParts) : 'Tidak ada data yang diproses.';

            return response()->json([
                'success'       => $isSuccess,
                'message'       => $message,
                'success_count' => $successCount,
                'skipped_count' => count($skipped),
                'error_count'   => count($allErrors),
                'errors'        => $allErrors,
                'skipped'       => $skipped,
            ]);
        } catch (\Exception $e) {
            \Log::error('KIBB Import Exception: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'error'   => 'Terjadi kesalahan saat memproses file: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function preview()
    {
        return response()->json(['error' => 'Use upload endpoint instead'], 400);
    }

    public function confirm(Request $request)
    {
        return response()->json(['error' => 'Direct import is handled via upload endpoint'], 400);
    }

    public function cancel(Request $request)
    {
        return response()->json(['success' => true]);
    }

    protected function generateInventoryNumber(): string
    {
        $latest = Item::withTrashed()
            ->where('inventory_number', 'like', 'INV-%')
            ->orderByRaw('CAST(SUBSTRING(inventory_number, 5) AS UNSIGNED) DESC')
            ->value('inventory_number');

        if ($latest) {
            $number = (int) substr($latest, 4) + 1;
        } else {
            $number = 1;
        }

        return 'INV-' . str_pad((string)$number, 4, '0', STR_PAD_LEFT);
    }
}
