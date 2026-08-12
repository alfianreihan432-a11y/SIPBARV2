<?php

namespace App\Livewire;

use App\Models\BorrowingRequest;
use App\Services\BorrowingStateMachine;
use App\Services\QRCodeService;
use App\Services\WhatsAppNotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class QRScanner extends Component
{
    public ?string $scannedToken = null;
    public ?BorrowingRequest $transaction = null;
    public string $action = ''; // 'checkout', 'checkin', 'read-only'
    public string $returnCondition = 'good';
    public string $returnNotes = '';
    
    protected $listeners = ['resetScanner' => 'reset'];
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function processQR(string $token)
    {
        $this->scannedToken = $token;
        
        // Lookup token using QRCodeService
        $qrCodeService = app(QRCodeService::class);
        $borrowingRequest = $qrCodeService->findByToken($token);
        
        if (!$borrowingRequest) {
            session()->flash('error', 'QR Code tidak ditemukan di sistem. Pastikan QR Code berasal dari SIPBAR.');
            $this->transaction = null;
            $this->action = '';
            return;
        }
        
        // Record the scan
        $qrCodeService->recordScan($borrowingRequest);
        
        // Determine action based on current status
        $this->transaction = $borrowingRequest->load(['user', 'item', 'teacher']);
        
        switch ($borrowingRequest->status) {
            case BorrowingRequest::STATUS_APPROVED:
                $this->action = 'checkout';
                break;
                
            case BorrowingRequest::STATUS_BORROWED:
                $this->action = 'checkin';
                break;
                
            case BorrowingRequest::STATUS_PENDING:
            case BorrowingRequest::STATUS_REJECTED:
            case BorrowingRequest::STATUS_RETURNED:
                $this->action = 'read-only';
                break;
                
            default:
                $this->action = 'read-only';
        }
        
        Log::info('QR scanned', [
            'token' => $token,
            'borrowing_request_id' => $borrowingRequest->id,
            'status' => $borrowingRequest->status,
            'action' => $this->action,
            'scanned_by' => Auth::id()
        ]);
    }
    
    public function confirmCheckout()
    {
        if (!$this->transaction || $this->action !== 'checkout') {
            session()->flash('error', 'Aksi tidak valid');
            return;
        }
        
        try {
            DB::beginTransaction();
            
            $stateMachine = app(BorrowingStateMachine::class);
            $whatsappService = app(WhatsAppNotificationService::class);
            
            // Transition to borrowed status
            $stateMachine->transitionTo(
                $this->transaction,
                BorrowingRequest::STATUS_BORROWED,
                Auth::id()
            );
            
            // Send WhatsApp notification (non-blocking)
            try {
                $whatsappService->notifyCheckout($this->transaction);
            } catch (\Exception $e) {
                Log::error('WhatsApp notification failed for checkout', [
                    'borrowing_request_id' => $this->transaction->id,
                    'error' => $e->getMessage()
                ]);
            }
            
            DB::commit();
            
            session()->flash('success', 'Checkout berhasil! Barang telah diserahkan kepada ' . $this->transaction->user->name);
            
            $this->reset();
            $this->dispatch('resetScanner');
            
        } catch (\App\Exceptions\InvalidStateTransitionException $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed', [
                'borrowing_request_id' => $this->transaction->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Terjadi kesalahan saat checkout: ' . $e->getMessage());
        }
    }
    
    public function confirmCheckin()
    {
        $this->validate([
            'returnCondition' => 'required|in:good,damaged,lost',
            'returnNotes' => 'nullable|string|max:500',
        ], [
            'returnCondition.required' => 'Kondisi barang harus dipilih',
            'returnCondition.in' => 'Kondisi barang tidak valid',
            'returnNotes.max' => 'Catatan maksimal 500 karakter',
        ]);
        
        if (!$this->transaction || $this->action !== 'checkin') {
            session()->flash('error', 'Aksi tidak valid');
            return;
        }
        
        try {
            DB::beginTransaction();
            
            $stateMachine = app(BorrowingStateMachine::class);
            $whatsappService = app(WhatsAppNotificationService::class);
            
            // Transition to returned status
            $stateMachine->transitionTo(
                $this->transaction,
                BorrowingRequest::STATUS_RETURNED,
                Auth::id()
            );
            
            // Update return condition and notes
            $this->transaction->update([
                'return_condition' => $this->returnCondition,
                'return_notes' => $this->returnNotes,
            ]);
            
            // Send WhatsApp notification (non-blocking)
            try {
                $whatsappService->notifyReturned($this->transaction);
            } catch (\Exception $e) {
                Log::error('WhatsApp notification failed for checkin', [
                    'borrowing_request_id' => $this->transaction->id,
                    'error' => $e->getMessage()
                ]);
            }
            
            DB::commit();
            
            session()->flash('success', 'Checkin berhasil! Barang telah dikembalikan oleh ' . $this->transaction->user->name);
            
            $this->reset();
            $this->dispatch('resetScanner');
            
        } catch (\App\Exceptions\InvalidStateTransitionException $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkin failed', [
                'borrowing_request_id' => $this->transaction->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Terjadi kesalahan saat checkin: ' . $e->getMessage());
        }
    }
    
    public function cancel()
    {
        $this->reset();
        $this->dispatch('resetScanner');
    }
    
    public function reset()
    {
        $this->scannedToken = null;
        $this->transaction = null;
        $this->action = '';
        $this->returnCondition = 'good';
        $this->returnNotes = '';
    }

    public function render()
    {
        return view('livewire.qr-scanner');
    }
}
