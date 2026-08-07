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

        if ($request->status !== 'qr_ready') {
            session()->flash('error', 'Status peminjaman tidak valid untuk persetujuan.');
            return;
        }

        // Check stock availability
        $item = $request->item;
        if ($item->stock < $request->quantity) {
            session()->flash('error', 'Stok barang tidak mencukupi.');
            return;
        }

        // Update request status
        $request->update([
            'status' => 'borrowed',
            'borrowed_at' => now(),
        ]);

        // Reduce stock
        $item->update([
            'stock' => $item->stock - $request->quantity,
        ]);

        // Mark QR as scanned
        $request->qrCode->update([
            'scanned_at' => now(),
        ]);

        session()->flash('success', 'Pengambilan barang berhasil disetujui. Stok telah dikurangi.');
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

        // Update request status
        $request->update([
            'status' => 'returned',
            'returned_at' => now(),
            'return_condition' => $this->returnCondition,
            'return_notes' => $this->returnNotes,
        ]);

        // Increase stock
        $item = $request->item;
        $item->update([
            'stock' => $item->stock + $request->quantity,
        ]);

        // Deactivate QR code
        $request->qrCode->update([
            'is_active' => false,
        ]);

        session()->flash('success', 'Pengembalian barang berhasil diselesaikan. Stok telah dikembalikan.');
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
