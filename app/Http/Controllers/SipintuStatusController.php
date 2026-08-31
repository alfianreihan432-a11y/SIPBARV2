<?php

namespace App\Http\Controllers;

use App\Services\SipintuService;
use Illuminate\Http\Request;

/**
 * SipintuStatusController
 *
 * Endpoint internal AJAX untuk mengecek status koneksi SiPintu
 * dari halaman Pengaturan admin.
 */
class SipintuStatusController extends Controller
{
    public function __construct(protected SipintuService $sipintu) {}

    /**
     * GET /api/internal/sipintu/status
     * Diakses via AJAX dari halaman Pengaturan Tampilan.
     */
    public function status(Request $request)
    {
        $pingResult = $this->sipintu->ping();

        return response()->json([
            'connected'    => $pingResult['online'] ?? false,
            'status_label' => ($pingResult['online'] ?? false) ? 'Connected' : 'Disconnected',
            'error'        => $pingResult['error'] ?? null,
            'gateway_data' => $pingResult['data'] ?? null,
            'checked_at'   => now()->toISOString(),
        ]);
    }

    /**
     * POST /api/internal/sipintu/validate
     * Validasi kredensial klien SiPintu.
     */
    public function validate(Request $request)
    {
        $result = $this->sipintu->validateClient();

        return response()->json([
            'valid'    => $result['success'],
            'data'     => $result['data'],
            'error'    => $result['error'],
        ]);
    }

    /**
     * GET /api/internal/sipintu/students
     * Ambil data siswa dari SiPintu Gateway.
     */
    public function students(Request $request)
    {
        $nis = $request->query('nis');
        $search = $request->query('search');
        $forceRefresh = $request->query('force_refresh', false);

        $result = $this->sipintu->getStudents(
            nis: $nis,
            search: $search,
            forceRefresh: filter_var($forceRefresh, FILTER_VALIDATE_BOOLEAN)
        );

        return response()->json([
            'success' => $result['success'],
            'data'    => $result['data'],
            'total'   => $result['total'],
            'error'   => $result['error'],
            'cached'  => $result['cached'] ?? false,
        ]);
    }

    /**
     * GET /api/internal/sipintu/teachers
     * Ambil data guru dari SiPintu Gateway.
     */
    public function teachers(Request $request)
    {
        $nip = $request->query('nip');
        $search = $request->query('search');
        $forceRefresh = $request->query('force_refresh', false);

        $result = $this->sipintu->getTeachers(
            nip: $nip,
            search: $search,
            forceRefresh: filter_var($forceRefresh, FILTER_VALIDATE_BOOLEAN)
        );

        return response()->json([
            'success' => $result['success'],
            'data'    => $result['data'],
            'total'   => $result['total'],
            'error'   => $result['error'],
            'cached'  => $result['cached'] ?? false,
        ]);
    }

    /**
     * POST /api/internal/sipintu/sync
     * Trigger sinkronisasi data dari SiPintu ke database lokal.
     */
    public function sync(Request $request)
    {
        $forceRefresh = $request->input('force_refresh', false);
        $batchSize = $request->input('batch_size', 100);

        try {
            // Run sync command asynchronously
            $exitCode = \Illuminate\Support\Facades\Artisan::call('sipintu:sync-users', [
                '--force' => $forceRefresh,
                '--batch' => $batchSize,
            ]);

            $output = \Illuminate\Support\Facades\Artisan::output();

            return response()->json([
                'success' => $exitCode === 0,
                'message' => $exitCode === 0 ? 'Sinkronisasi selesai' : 'Sinkronisasi gagal',
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menjalankan sinkronisasi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
