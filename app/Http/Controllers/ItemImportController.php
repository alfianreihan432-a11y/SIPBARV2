<?php

namespace App\Http\Controllers;

use App\Imports\KibbImport;
use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

class ItemImportController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240',
        ]);

        try {
            $import = new KibbImport();
            
            // Load the file and find the KIBB sheet (exact match by name)
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($request->file('file')->getPathname());
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($request->file('file')->getPathname());

            // Get sheet by exact name using loop with index
            $kibbSheet = null;
            $kibbSheetIndex = null;
            $sheetNames = $spreadsheet->getSheetNames();
            \Log::info('KIBB Import - Available sheets: ' . implode(', ', $sheetNames));
            error_log('KIBB Import - Available sheets: ' . implode(', ', $sheetNames));

            foreach ($sheetNames as $i => $name) {
                $trimmedName = trim($name);
                $upperName = strtoupper($trimmedName);
                \Log::info("KIBB Import - Checking index $i: \"$name\" (trimmed: \"$trimmedName\", upper: \"$upperName\")");
                error_log("KIBB Import - Checking index $i: \"$name\" (trimmed: \"$trimmedName\", upper: \"$upperName\")");

                if ($upperName === 'KIBB') {
                    $kibbSheet = $spreadsheet->getSheet($i);
                    $kibbSheetIndex = $i;
                    \Log::info("KIBB Import - MATCH FOUND at index $i: \"$name\"");
                    error_log("KIBB Import - MATCH FOUND at index $i: \"$name\"");
                    break;
                }
            }

            if ($kibbSheet === null) {
                \Log::error('KIBB Import - Sheet KIBB not found. Available: ' . implode(', ', $sheetNames));
                error_log('KIBB Import - Sheet KIBB not found. Available: ' . implode(', ', $sheetNames));
                return response()->json([
                    'success' => false,
                    'error' => 'Sheet "KIBB" tidak ditemukan dalam file Excel. Sheet yang tersedia: ' . implode(', ', $sheetNames),
                    'debug_sheet_selected' => 'NONE',
                ], 400);
            }

            // Get the actual sheet name from the sheet object
            $actualSheetName = $kibbSheet->getTitle();
            \Log::info('KIBB Import - Selected sheet at index ' . $kibbSheetIndex . ': ' . $actualSheetName);
            error_log('KIBB Import - Selected sheet at index ' . $kibbSheetIndex . ': ' . $actualSheetName);

            // Extract data directly from the selected sheet
            $worksheet = $spreadsheet->getSheet($kibbSheetIndex);
            $dataArray = $worksheet->toArray();
            $collection = new \Illuminate\Support\Collection($dataArray);

            \Log::info('KIBB Import - Extracted ' . count($collection) . ' rows from sheet');
            error_log('KIBB Import - Extracted ' . count($collection) . ' rows from sheet');

            // Process the collection directly and save to DB
            $import->collection($collection);

            $previewData = $import->getPreviewData();
            $errors = $import->getErrors();
            $skipped = $import->getSkipped();

            // Get default category, location, and condition
            $defaultCategory = Category::where('name', 'Umum')->first();
            $defaultLocation = Location::where('building', 'Gudang Inventaris')->first();

            // Save items to database
            DB::beginTransaction();
            $successCount = 0;
            $errorCount = 0;
            $errorMessages = [];

            try {
                foreach ($previewData as $itemData) {
                    try {
                        $code = strtoupper('BRG-' . substr(md5(uniqid()), 0, 6));
                        $inventoryNumber = $this->generateInventoryNumber();

                        Item::create([
                            'code' => $code,
                            'inventory_number' => $inventoryNumber,
                            'name' => $itemData['name'] ?? 'Tanpa Nama',
                            'description' => $itemData['description'] ?? '',
                            'category_id' => $itemData['category_id'] ?? $defaultCategory?->id,
                            'location_id' => $itemData['location_id'] ?? $defaultLocation?->id,
                            'condition' => 'Baik',
                            'status' => 'Tersedia',
                            'quantity' => 1,
                            'needs_review' => $itemData['needs_review'] ?? false,
                            'kode_kibb' => $itemData['kode_kibb'] ?? null,
                        ]);

                        $successCount++;
                    } catch (\Exception $e) {
                        $errorCount++;
                        $errorMessages[] = "Baris {$itemData['row']}: " . $e->getMessage();
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'error' => 'Gagal menyimpan data: ' . $e->getMessage(),
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => "{$successCount} barang berhasil diimport. " . ($errorCount > 0 ? "{$errorCount} gagal." : ''),
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'errors' => $errorMessages,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function preview()
    {
        // This method is no longer needed for the new flow
        // Preview is now handled via JSON response from upload()
        return response()->json(['error' => 'Use upload endpoint instead'], 400);
    }

    public function confirm(Request $request)
    {
        $batchId = $request->input('batch_id');
        $sessionData = Session::get($batchId);

        if (!$sessionData || empty($sessionData['preview'])) {
            return response()->json([
                'success' => false,
                'error' => 'Sesi import tidak ditemukan atau sudah kadaluarsa.',
            ], 400);
        }

        $previewData = $sessionData['preview'];
        $skipped = $sessionData['skipped'];

        // Get updated data from request (user may have edited category/location)
        $updatedData = $request->input('items', []);

        // Ensure default category exists
        $defaultCategory = Category::firstOrCreate(
            ['name' => 'Belum Dikategorikan'],
            ['icon' => '📦', 'color' => '#9ca3af', 'description' => 'Kategori default untuk barang yang belum dikategorikan']
        );

        $successCount = 0;
        $errorCount = 0;
        $errorMessages = [];

        DB::beginTransaction();

        try {
            foreach ($previewData as $index => $itemData) {
                try {
                    // Apply user edits if any
                    $categoryId = $itemData['category_id'];
                    $locationId = $itemData['location_id'];

                    if (isset($updatedData[$index])) {
                        $categoryId = $updatedData[$index]['category_id'] ?: null;
                        $locationId = $updatedData[$index]['location_id'] ?: null;
                    }

                    // Use default category if null
                    if (!$categoryId) {
                        $categoryId = $defaultCategory->id;
                    }

                    // Generate code and inventory_number using existing logic
                    $code = strtoupper('BRG-' . substr(md5(uniqid()), 0, 6));
                    $inventoryNumber = $this->generateInventoryNumber();

                    // Create item
                    Item::create([
                        'code' => $code,
                        'inventory_number' => $inventoryNumber,
                        'name' => $itemData['name'],
                        'description' => $itemData['description'],
                        'category_id' => $categoryId,
                        'location_id' => $locationId,
                        'brand' => $itemData['merk'],
                        'type' => $itemData['tipe'],
                        'purchase_year' => $itemData['purchase_year'],
                        'price' => $itemData['price'],
                        'condition' => $itemData['condition'],
                        'status' => $itemData['status'],
                        'stock' => $itemData['stock'],
                        'kode_kibb' => $itemData['kode_kibb'],
                        'nomor_reg' => $itemData['nomor_reg'],
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    $errorMessages[] = "Baris {$itemData['row']}: " . $e->getMessage();
                }
            }

            DB::commit();

            // Clear session
            Session::forget($batchId);

            return response()->json([
                'success' => true,
                'success_count' => $successCount,
                'skipped_count' => count($skipped),
                'error_count' => $errorCount,
                'error_messages' => $errorMessages,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Gagal menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
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

        return 'INV-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function cancel(Request $request)
    {
        $batchId = $request->input('batch_id');
        if ($batchId) {
            Session::forget($batchId);
        }
        return response()->json(['success' => true]);
    }
}
