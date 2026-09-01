<?php

namespace App\Livewire;

use App\Models\BorrowingRequest;
use App\Models\Item;
use App\Models\QRCode;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminQRScanner extends Component
{
    public $qrCode = '';
    public $scannedRequest = null;
    public $showDetails = false;
    public $returnCondition = 'good';
    public $returnNotes = '';
    public $isReturnMode = false;

    public function scanQRCode()
    {
        $this->validate([
            'qrCode' => 'required|string',
        ]);

        $qrCodeRecord = QRCode::where('code', $this->qrCode)
            ->with('borrowingRequest.user', 'borrowingRequest.item')
            ->first();

        if (!$qrCodeRecord) {
            session()->flash('error', 'QR Code tidak valid atau tidak ditemukan.');
            $this->scannedRequest = null;
            $this->showDetails = false;
            return;
        }

        if (!$qrCodeRecord->isValid()) {
            session()->flash('error', 'QR Code sudah tidak aktif atau telah kadaluarsa.');
            $this->scannedRequest = null;
            $this->showDetails = false;
            return;
        }

        $request = $qrCodeRecord->borrowingRequest;

        if ($request->status === 'borrowed') {
            $this->isReturnMode = true;
        } else {
            $this->isReturnMode = false;
        }

        $this->scannedRequest = $request;
        $this->showDetails = true;
        $this->qrCode = '';
    }

    public function approveBorrowing()
    {
        if (!$this->scannedRequest) {
            return;
        }

        $request = $this->scannedRequest;

        if (! in_array($request->status, ['approved', 'qr_ready'])) {
            session()->flash('error', 'Status peminjaman tidak valid untuk persetujuan.');
            return;
        }

        // Check stock availability via BorrowingApprovalService
        $availableStock = app(\App\Services\BorrowingApprovalService::class)->getAvailableStock($request->item);
        if ($availableStock < $request->quantity && $request->status !== 'approved') {
            session()->flash('error', 'Stok barang tidak mencukupi.');
            return;
        }

        // Use BorrowingStateMachine
        $stateMachine = app(\App\Services\BorrowingStateMachine::class);
        try {
            $stateMachine->transitionTo($request, BorrowingRequest::STATUS_BORROWED, Auth::id());
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memproses peminjaman: ' . $e->getMessage());
            return;
        }

        // Mark QR as scanned
        if ($request->qrCode) {
            $request->qrCode->update([
                'scanned_at' => now(),
            ]);
        }

        session()->flash('success', 'Pengambilan barang berhasil disetujui. Status stok telah diperbarui.');
        $this->scannedRequest = null;
        $this->showDetails = false;
    }

    public function processReturn()
    {
        $this->validate([
            'returnCondition' => 'required|in:good,damaged,lost',
            'returnNotes' => 'nullable|string|max:500',
        ]);

        if (!$this->scannedRequest) {
            return;
        }

        $request = $this->scannedRequest;

        if ($request->status !== 'borrowed') {
            session()->flash('error', 'Status peminjaman tidak valid untuk pengembalian.');
            return;
        }

        // Use BorrowingStateMachine to return and recalculate item status
        $stateMachine = app(\App\Services\BorrowingStateMachine::class);
        try {
            $stateMachine->transitionTo($request, BorrowingRequest::STATUS_RETURNED, Auth::id());
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
            return;
        }

        // Update return condition and notes
        $request->update([
            'return_condition' => $this->returnCondition,
            'return_notes' => $this->returnNotes,
        ]);

        // Deactivate QR code if exists
        if ($request->qrCode) {
            $request->qrCode->update([
                'is_active' => false,
            ]);
        }

        session()->flash('success', 'Pengembalian barang berhasil diselesaikan. Stok tersedia telah dipulihkan.');
        $this->scannedRequest = null;
        $this->showDetails = false;
        $this->isReturnMode = false;
        $this->returnCondition = 'good';
        $this->returnNotes = '';
    }

    public function cancel()
    {
        $this->scannedRequest = null;
        $this->showDetails = false;
        $this->isReturnMode = false;
        $this->qrCode = '';
    }

    public function render()
    {
        return view('livewire.admin-qr-scanner');
    }
}
