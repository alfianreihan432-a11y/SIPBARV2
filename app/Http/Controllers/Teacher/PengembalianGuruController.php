<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\BorrowingRequest;
use App\Models\ItemReturn;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PengembalianGuruController extends Controller
{
    /**
     * Display teacher's active borrowings for return
     */
    public function index(): View
    {
        $activeBorrowings = BorrowingRequest::where('user_id', Auth::id())
            ->where('tipe_peminjam', 'guru')
            ->whereIn('status', [BorrowingRequest::STATUS_BORROWED, BorrowingRequest::STATUS_APPROVED])
            ->with(['item', 'approvedByKajur'])
            ->latest()
            ->get();

        $completedReturns = BorrowingRequest::where('user_id', Auth::id())
            ->where('tipe_peminjam', 'guru')
            ->where('status', BorrowingRequest::STATUS_RETURNED)
            ->with(['item', 'approvedByKajur'])
            ->latest()
            ->get();

        return view('pages.guru.pengembalian-guru', [
            'activeBorrowings' => $activeBorrowings,
            'completedReturns' => $completedReturns,
        ]);
    }

    /**
     * Show form to request return for a specific borrowing
     */
    public function create(int $id): View
    {
        $borrowing = BorrowingRequest::where('user_id', Auth::id())
            ->where('tipe_peminjam', 'guru')
            ->whereIn('status', [BorrowingRequest::STATUS_BORROWED, BorrowingRequest::STATUS_APPROVED])
            ->with(['item.category'])
            ->findOrFail($id);

        return view('pages.guru.pengembalian-guru-create', [
            'borrowing' => $borrowing,
        ]);
    }

    /**
     * Store return request
     */
    public function store(Request $request, int $id): RedirectResponse
    {
        $borrowing = BorrowingRequest::where('user_id', Auth::id())
            ->where('tipe_peminjam', 'guru')
            ->whereIn('status', [BorrowingRequest::STATUS_BORROWED, BorrowingRequest::STATUS_APPROVED])
            ->findOrFail($id);

        $request->validate([
            'kondisi_barang'   => 'nullable|in:baik,rusak_ringan,rusak_berat,hilang,Baik,Rusak Ringan,Rusak Berat,Hilang',
            'return_condition' => 'nullable|in:baik,rusak_ringan,rusak_berat,hilang,Baik,Rusak Ringan,Rusak Berat,Hilang',
            'catatan'          => 'nullable|string|max:1000',
            'return_notes'     => 'nullable|string|max:1000',
            'foto_bukti'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'kondisi_barang.in' => 'Kondisi barang tidak valid.',
            'catatan.max'       => 'Catatan maksimal 1000 karakter.',
            'return_notes.max'  => 'Catatan maksimal 1000 karakter.',
            'foto_bukti.image'  => 'File bukti harus berupa gambar.',
            'foto_bukti.mimes'  => 'Format foto yang didukung: jpg, jpeg, png, webp.',
            'foto_bukti.max'    => 'Ukuran foto maksimal 2MB.',
        ]);

        $rawCondition = $request->input('kondisi_barang') ?? $request->input('return_condition') ?? 'baik';

        $conditionDisplayMap = [
            'baik'         => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat'  => 'Rusak Berat',
            'hilang'       => 'Hilang',
            'Baik'         => 'Baik',
            'Rusak Ringan' => 'Rusak Ringan',
            'Rusak Berat'  => 'Rusak Berat',
            'Hilang'       => 'Hilang',
        ];

        $conditionEnumMap = [
            'baik'         => 'baik',
            'rusak_ringan' => 'rusak_ringan',
            'rusak_berat'  => 'rusak_berat',
            'hilang'       => 'hilang',
            'Baik'         => 'baik',
            'Rusak Ringan' => 'rusak_ringan',
            'Rusak Berat'  => 'rusak_berat',
            'Hilang'       => 'hilang',
        ];

        $conditionDisplay = $conditionDisplayMap[$rawCondition] ?? 'Baik';
        $conditionEnum = $conditionEnumMap[$rawCondition] ?? 'baik';
        $notes = $request->input('catatan') ?? $request->input('return_notes') ?? null;

        // Handle upload foto bukti
        $fotoPath = null;
        if ($request->hasFile('foto_bukti')) {
            $fotoPath = $request->file('foto_bukti')->store('returns', 'public');
        }

        // Update BorrowingRequest
        $borrowing->update([
            'return_condition' => $conditionDisplay,
            'return_notes'     => $notes,
            'foto_bukti'       => $fotoPath,
            'status'           => BorrowingRequest::STATUS_RETURNED,
            'returned_at'      => now(),
        ]);

        // Create ItemReturn record — route to Kepala Jurusan, NOT Admin
        ItemReturn::create([
            'borrowing_request_id' => $borrowing->id,
            'user_id'              => Auth::id(),
            'tipe_peminjam'        => 'guru',
            'kajur_id'             => $borrowing->approved_by_kajur_id, // target verifikator
            'kondisi_barang'       => $conditionEnum,
            'catatan'              => $notes,
            'foto_bukti'           => $fotoPath,
            'status'               => ItemReturn::STATUS_MENUNGGU,
        ]);

        // Don't restore item stock yet - wait for Kepala Jurusan verification
        // Item stock will be restored when Kepala Jurusan verifies the return

        return redirect()->route('teacher.pengembalian-guru')
            ->with('success', 'Pengajuan pengembalian berhasil dikirim! Menunggu verifikasi Kepala Jurusan.');
    }

    /**
     * Show return history
     */
    public function history(Request $request): View
    {
        $query = BorrowingRequest::where('user_id', Auth::id())
            ->where('tipe_peminjam', 'guru')
            ->where('status', BorrowingRequest::STATUS_RETURNED)
            ->with(['item', 'itemWithTrashed', 'approvedByKajur', 'items.item']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->whereHas('item', function($iq) use ($search) {
                    $iq->where('name', 'like', "%{$search}%")
                       ->orWhere('code', 'like', "%{$search}%");
                })->orWhereHas('itemWithTrashed', function($iq) use ($search) {
                    $iq->where('name', 'like', "%{$search}%")
                       ->orWhere('code', 'like', "%{$search}%");
                })->orWhereHas('items.item', function($miq) use ($search) {
                    $miq->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('returned_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('returned_at', '<=', $request->date_to);
        }

        $returns = $query->latest('returned_at')->paginate(20)->withQueryString();

        return view('pages.guru.pengembalian-guru-history', [
            'returns' => $returns,
        ]);
    }
}
