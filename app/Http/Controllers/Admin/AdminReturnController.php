<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BorrowingRequest;
use App\Models\ItemReturn;
use App\Models\Notification;
use App\Services\ReturnStockService;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminReturnController extends Controller
{
    /**
     * Daftar Pengajuan Pengembalian Masuk
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'semua');
        $search = $request->query('q');
        $isSuperadmin = Auth::check() && Auth::user()->hasRole('superadmin');

        // Admin hanya menangani pengembalian SISWA untuk peminjaman ke Admin
        $query = ItemReturn::siswa()->where(function ($q) {
            $q->whereDoesntHave('borrowingRequest', function ($bq) {
                $bq->where('lender_type', 'kajur');
            });
        })->with([
            'borrowingRequest.item.category',
            'user',
            'verifier'
        ])->latest();

        if ($status !== 'semua' && in_array($status, ['menunggu', 'disetujui', 'ditolak'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('nis', 'like', "%{$search}%");
                })->orWhereHas('borrowingRequest.item', function ($iq) use ($search) {
                    $iq->where('name', 'like', "%{$search}%")
                       ->orWhere('code', 'like', "%{$search}%");
                });
            });
        }

        $returns = $query->paginate(12)->withQueryString();

        // Statistik khusus siswa
        $countSemua    = ItemReturn::siswa()->count();
        $countMenunggu = ItemReturn::siswa()->where('status', ItemReturn::STATUS_MENUNGGU)->count();
        $countDisetujui= ItemReturn::siswa()->where('status', ItemReturn::STATUS_DISETUJUI)->count();
        $countDitolak  = ItemReturn::siswa()->where('status', ItemReturn::STATUS_DITOLAK)->count();

        $view = $isSuperadmin ? 'pages.superadmin.returns' : 'pages.admin.returns';

        return view($view, compact(
            'returns',
            'status',
            'search',
            'countSemua',
            'countMenunggu',
            'countDisetujui',
            'countDitolak'
        ));
    }

    /**
     * Detail Pengajuan Pengembalian (AJAX / modal support)
     * Endpoint ini hanya dipanggil via fetch/AJAX — tidak punya halaman HTML penuh.
     */
    public function show($id)
    {
        $return = ItemReturn::with([
            'borrowingRequest.item.category',
            'user',
            'verifier'
        ])->findOrFail($id);

        if ($return->borrowingRequest && $return->borrowingRequest->isStudentLoanForKajur()) {
            abort(403, 'Pengembalian pinjaman Kajur hanya dapat diproses oleh Kajur pemberi pinjaman.');
        }

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $return,
                'photo_url' => $return->foto_bukti ? asset('storage/' . $return->foto_bukti) : null,
            ]);
        }

        // Endpoint ini dirancang untuk AJAX saja.
        // Akses langsung via browser tidak didukung.
        abort(404);
    }

    /**
     * Setujui Pengembalian Barang
     */
    public function approve(Request $request, $id)
    {
        $return = ItemReturn::with([
            'borrowingRequest.item',
            'borrowingRequest.items.item',
            'user',
        ])->findOrFail($id);

        if ($return->borrowingRequest && $return->borrowingRequest->isStudentLoanForKajur()) {
            abort(403, 'Pengembalian pinjaman Kajur hanya dapat diproses oleh Kajur pemberi pinjaman.');
        }

        // Guard: Admin tidak boleh verifikasi pengembalian GURU
        if ($return->tipe_peminjam === 'guru') {
            return back()->with('error', 'Pengembalian dari guru harus diverifikasi oleh Kepala Jurusan, bukan Admin.');
        }

        // Guard: Proteksi verifikasi ganda
        if ($return->status !== ItemReturn::STATUS_MENUNGGU) {
            return back()->with('error', 'Pengembalian ini sudah diverifikasi sebelumnya.');
        }

        // Peta kondisi internal (enum ItemReturn) ke label tampilan BorrowingRequest
        $conditionMap = [
            'baik'         => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat'  => 'Rusak Berat',
            'hilang'       => 'Hilang',
        ];
        $kondisi         = strtolower($request->input('kondisi', $return->kondisi_barang ?? 'baik'));
        $returnCondition = $conditionMap[$kondisi] ?? ($return->kondisi_barang ?? 'Baik');
        $adminId         = Auth::id();
        $now             = now();
        $borrowing       = $return->borrowingRequest;

        $returnStockService = app(ReturnStockService::class);

        // Guard: Multi-item dengan kondisi hilang atau rusak berat memerlukan penanganan manual per barang
        if ($returnStockService->isMultiItem($borrowing) && in_array($kondisi, ['hilang', 'rusak_berat', 'lost', 'rusak berat'])) {
            return back()->with('error', 'Pengembalian multi-item dengan kondisi rusak berat atau hilang memerlukan penanganan manual per barang.');
        }

        // Tangkap ringkasan nama barang SEBELUM transaction agar dapat dipakai di notifikasi.
        // itemSummary() menangani single-item maupun multi-item dengan benar.
        $itemSummary = $borrowing->itemSummary();

        try {
            // ── DB::transaction hanya untuk operasi DB ─────────────────────────
            DB::transaction(function () use ($return, $borrowing, $kondisi, $returnCondition, $adminId, $now, $itemSummary, $returnStockService) {
                // 1. Update status ItemReturn
                $return->update([
                    'status'             => ItemReturn::STATUS_DISETUJUI,
                    'diverifikasi_oleh'  => $adminId,
                    'tanggal_verifikasi' => $now,
                ]);

                // 2. Sinkronisasi BorrowingRequest → status 'returned'
                $borrowing->update([
                    'status'           => BorrowingRequest::STATUS_RETURNED,
                    'returned_at'      => $borrowing->returned_at ?? $now,
                    'return_condition' => $returnCondition,
                    'return_notes'     => $return->catatan ?? $borrowing->return_notes,
                    'checkin_by'       => $adminId,
                ]);

                // 3. Terapkan perubahan stok via ReturnStockService.
                //    Single-item dan multi-item menggunakan jalur kode yang sama
                //    melalui collectItemsWithQuantities() — tidak ada duplikasi blok.
                $returnStockService->applyStockForReturn($borrowing, $kondisi);

                // 4. Notifikasi in-app untuk Siswa.
                //    Menggunakan $itemSummary (sudah di-capture sebelum closure)
                //    agar multi-item tidak tampil "Barang tidak ditemukan".
                Notification::sendToUser(
                    $return->user_id,
                    'pengembalian_disetujui',
                    "Pengajuan pengembalian barang '{$itemSummary}' telah DISETUJUI oleh Admin. Terima kasih telah mengembalikan barang!",
                    ['item_return_id' => $return->id]
                );
            });

            // ── WhatsApp notification di LUAR transaction (setelah commit) ────
            // Dipindahkan ke luar agar kegagalan WA tidak me-rollback transaksi DB.
            try {
                $waService = app(WhatsAppNotificationService::class);
                $waService->notifyReturned($borrowing);
            } catch (\Exception $we) {
                Log::info('WA notification return (admin approve) ignored: ' . $we->getMessage());
            }

            return back()->with('success', 'Pengembalian barang berhasil disetujui! Status barang dan peminjaman telah diperbarui.');

        } catch (\Exception $e) {
            Log::error('Error approving return request: ' . $e->getMessage(), [
                'return_id' => $id,
                'trace'     => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Terjadi kesalahan saat menyetujui pengembalian: ' . $e->getMessage());
        }
    }

    /**
     * Tolak Pengembalian Barang
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_ditolak' => 'required|string|min:5|max:500',
        ], [
            'alasan_ditolak.required' => 'Wajib mengisi alasan penolakan pengembalian.',
            'alasan_ditolak.min'      => 'Alasan penolakan minimal 5 karakter.',
            'alasan_ditolak.max'      => 'Alasan penolakan maksimal 500 karakter.',
        ]);

        $return = ItemReturn::with(['borrowingRequest.item', 'borrowingRequest.items.itemWithTrashed', 'user'])->findOrFail($id);

        if ($return->borrowingRequest && $return->borrowingRequest->isStudentLoanForKajur()) {
            abort(403, 'Pengembalian pinjaman Kajur hanya dapat diproses oleh Kajur pemberi pinjaman.');
        }

        // Guard: Admin tidak boleh verifikasi pengembalian GURU
        if ($return->tipe_peminjam === 'guru') {
            return back()->with('error', 'Pengembalian dari guru harus diverifikasi oleh Kepala Jurusan, bukan Admin.');
        }

        if ($return->status !== ItemReturn::STATUS_MENUNGGU) {
            return back()->with('error', 'Pengembalian ini sudah diverifikasi sebelumnya.');
        }

        // Tangkap nama barang sebelum transaction
        $itemSummary = $return->borrowingRequest->itemSummary();

        try {
            DB::transaction(function () use ($return, $request, $itemSummary) {
                $now     = now();
                $adminId = Auth::id();

                // 1. Update status ItemReturn menjadi ditolak
                $return->update([
                    'status'             => ItemReturn::STATUS_DITOLAK,
                    'alasan_ditolak'     => $request->alasan_ditolak,
                    'diverifikasi_oleh'  => $adminId,
                    'tanggal_verifikasi' => $now,
                ]);

                // 2. Status peminjaman BorrowingRequest TETAP 'borrowed' agar siswa dapat mengajukan ulang

                // 3. Notifikasi in-app untuk Siswa (menggunakan itemSummary agar nama barang jelas)
                Notification::sendToUser(
                    $return->user_id,
                    'pengembalian_ditolak',
                    "Pengajuan pengembalian barang '{$itemSummary}' DITOLAK. Alasan: {$request->alasan_ditolak}. Silakan ajukan ulang dengan data yang sesuai.",
                    ['item_return_id' => $return->id, 'alasan' => $request->alasan_ditolak]
                );
            });

            return back()->with('success', 'Pengajuan pengembalian telah ditolak. Siswa telah menerima pemberitahuan alasan penolakan.');

        } catch (\Exception $e) {
            Log::error('Error rejecting return request: ' . $e->getMessage(), [
                'return_id' => $id,
                'trace'     => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Terjadi kesalahan saat menolak pengembalian: ' . $e->getMessage());
        }
    }
}
