<div class="max-w-4xl mx-auto p-4 md:p-6">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-6">Scanner QR Code Peminjaman</h1>
    
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 bg-emerald-50 dark:bg-emerald-900/30 border-l-4 border-emerald-500 p-4 rounded-lg animate-slide-down">
            <div class="flex items-start">
                <svg class="h-6 w-6 text-emerald-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 p-4 rounded-lg animate-slide-down">
            <div class="flex items-start">
                <svg class="h-6 w-6 text-red-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
            </div>
        </div>
    @endif
    
    {{-- Camera Scanner --}}
    @if(!$transaction)
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border-2 border-gray-200 dark:border-slate-700 p-6 mb-6">
        <div class="flex items-center gap-3 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
            </svg>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Arahkan kamera ke QR Code</h2>
        </div>
        
        <div id="qr-reader" class="w-full rounded-lg overflow-hidden border-2 border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900"></div>
        
        <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <p class="text-sm text-blue-800 dark:text-blue-200">
                <strong class="font-semibold">Petunjuk:</strong> Pastikan QR Code terlihat jelas dalam bingkai kamera. Scanner akan otomatis mendeteksi QR Code.
            </p>
        </div>
    </div>
    @endif
    
    {{-- Transaction Details & Actions --}}
    @if($transaction)
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border-2 
        @if($action === 'checkout') border-teal-500 @elseif($action === 'checkin') border-blue-500 @else border-gray-300 dark:border-slate-600 @endif">
        
        {{-- Header --}}
        <div class="p-6 border-b border-gray-200 dark:border-slate-700 
            @if($action === 'checkout') bg-teal-50 dark:bg-teal-900/20 @elseif($action === 'checkin') bg-blue-50 dark:bg-blue-900/20 @else bg-gray-50 dark:bg-slate-700/30 @endif">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Detail Peminjaman</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        @if($action === 'checkout')
                            Konfirmasi serah terima barang
                        @elseif($action === 'checkin')
                            Konfirmasi pengembalian barang
                        @else
                            Transaksi ini sudah selesai atau belum disetujui
                        @endif
                    </p>
                </div>
                <span class="px-3 py-1.5 rounded-lg text-xs font-bold
                    @if($transaction->status === 'approved') bg-emerald-100 text-emerald-800 border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300
                    @elseif($transaction->status === 'borrowed') bg-blue-100 text-blue-800 border border-blue-200 dark:bg-blue-900/30 dark:text-blue-300
                    @elseif($transaction->status === 'returned') bg-gray-100 text-gray-800 border border-gray-200 dark:bg-gray-900/30 dark:text-gray-300
                    @elseif($transaction->status === 'rejected') bg-red-100 text-red-800 border border-red-200 dark:bg-red-900/30 dark:text-red-300
                    @else bg-amber-100 text-amber-800 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 @endif">
                    {{ ucfirst($transaction->status) }}
                </span>
            </div>
        </div>
        
        {{-- Details Grid --}}
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Nama Siswa</label>
                <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $transaction->user->name }}</p>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Kelas</label>
                <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $transaction->user->kelas ?? '-' }} {{ $transaction->user->jurusan ?? '' }}</p>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Barang</label>
                <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $transaction->item->name }}</p>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Jumlah</label>
                <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $transaction->quantity }} unit</p>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Tanggal Pinjam</label>
                <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($transaction->borrow_date)->format('d M Y') }}</p>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Tanggal Kembali</label>
                <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($transaction->return_date)->format('d M Y') }} @if($transaction->return_time) · {{ $transaction->return_time }}@endif</p>
            </div>
            
            @if($transaction->checkout_at)
            <div>
                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Waktu Checkout</label>
                <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($transaction->checkout_at)->format('d M Y H:i') }}</p>
            </div>
            @endif
            
            @if($transaction->returned_at)
            <div>
                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Waktu Checkin</label>
                <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($transaction->returned_at)->format('d M Y H:i') }}</p>
            </div>
            @endif
        </div>
        
        @if($transaction->purpose)
        <div class="px-6 pb-6">
            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Keperluan</label>
            <p class="mt-1 text-sm text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-slate-700/50 p-3 rounded-lg">{{ $transaction->purpose }}</p>
        </div>
        @endif
        
        {{-- Return Condition Form (for checkin) --}}
        @if($action === 'checkin')
        <div class="px-6 pb-6">
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kondisi Barang Saat Dikembalikan</label>
            <div class="grid grid-cols-3 gap-3 mb-4">
                <label class="relative flex items-center justify-center p-3 border-2 rounded-lg cursor-pointer transition-all
                    {{ $returnCondition === 'good' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : 'border-gray-300 dark:border-slate-600 hover:border-emerald-300' }}">
                    <input type="radio" wire:model="returnCondition" value="good" class="sr-only">
                    <div class="text-center">
                        <div class="text-2xl mb-1">✅</div>
                        <div class="text-sm font-semibold {{ $returnCondition === 'good' ? 'text-emerald-700 dark:text-emerald-300' : 'text-gray-700 dark:text-gray-300' }}">Baik</div>
                    </div>
                </label>
                <label class="relative flex items-center justify-center p-3 border-2 rounded-lg cursor-pointer transition-all
                    {{ $returnCondition === 'damaged' ? 'border-amber-500 bg-amber-50 dark:bg-amber-900/20' : 'border-gray-300 dark:border-slate-600 hover:border-amber-300' }}">
                    <input type="radio" wire:model="returnCondition" value="damaged" class="sr-only">
                    <div class="text-center">
                        <div class="text-2xl mb-1">⚠️</div>
                        <div class="text-sm font-semibold {{ $returnCondition === 'damaged' ? 'text-amber-700 dark:text-amber-300' : 'text-gray-700 dark:text-gray-300' }}">Rusak</div>
                    </div>
                </label>
                <label class="relative flex items-center justify-center p-3 border-2 rounded-lg cursor-pointer transition-all
                    {{ $returnCondition === 'lost' ? 'border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-gray-300 dark:border-slate-600 hover:border-red-300' }}">
                    <input type="radio" wire:model="returnCondition" value="lost" class="sr-only">
                    <div class="text-center">
                        <div class="text-2xl mb-1">❌</div>
                        <div class="text-sm font-semibold {{ $returnCondition === 'lost' ? 'text-red-700 dark:text-red-300' : 'text-gray-700 dark:text-gray-300' }}">Hilang</div>
                    </div>
                </label>
            </div>
            @error('returnCondition')
                <p class="text-sm text-red-600 dark:text-red-400 mb-2">{{ $message }}</p>
            @enderror
            
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Catatan (opsional)</label>
            <textarea wire:model="returnNotes" rows="3" maxlength="500"
                      class="w-full px-4 py-3 border-2 border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none transition-colors"
                      placeholder="Tambahkan catatan jika ada kerusakan atau kehilangan..."></textarea>
            @error('returnNotes')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
        @endif
        
        {{-- Action Buttons --}}
        <div class="p-6 bg-gray-50 dark:bg-slate-700/30 border-t border-gray-200 dark:border-slate-700 flex gap-3">
            @if($action === 'checkout')
                <button wire:click="confirmCheckout" 
                        class="flex-1 px-6 py-3 bg-teal-600 hover:bg-teal-700 active:bg-teal-800 text-white rounded-lg transition-colors font-semibold shadow-sm flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Konfirmasi Serah Terima
                </button>
            @elseif($action === 'checkin')
                <button wire:click="confirmCheckin" 
                        class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-lg transition-colors font-semibold shadow-sm flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Konfirmasi Pengembalian
                </button>
            @endif
            
            <button wire:click="cancel" 
                    class="px-6 py-3 border-2 border-gray-300 dark:border-slate-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors font-semibold">
                {{ $action === 'read-only' ? 'Tutup' : 'Batal' }}
            </button>
        </div>
    </div>
    @endif
</div>

{{-- QR Scanner JavaScript --}}
@script
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let html5QrcodeScanner = null;
    
    function initScanner() {
        // Clear previous scanner if exists
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear().catch(() => {});
        }
        
        // Initialize new scanner
        html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader",
            { 
                fps: 10, 
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            },
            false
        );
        
        html5QrcodeScanner.render((decodedText) => {
            // Call Livewire method
            $wire.call('processQR', decodedText);
            
            // Clear scanner after successful scan
            html5QrcodeScanner.clear().catch(() => {});
        }, (error) => {
            // Silently handle scan errors (expected during scanning)
        });
    }
    
    // Initialize scanner on page load
    if (!@json($transaction)) {
        initScanner();
    }
    
    // Reinitialize scanner when resetScanner event is dispatched
    Livewire.on('resetScanner', () => {
        setTimeout(() => {
            initScanner();
        }, 500);
    });
</script>
@endscript
