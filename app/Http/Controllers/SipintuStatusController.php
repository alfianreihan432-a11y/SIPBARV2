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
}
