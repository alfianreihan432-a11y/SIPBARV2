<div class="bg-white rounded-xl shadow-lg p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">
        @if($isReturnMode)
            Scan QR Code Pengembalian
        @else
            Scan QR Code Pengambilan Barang
        @endif
    </h2>

    @if(!$showDetails)
        <div class="max-w-md mx-auto">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Masukkan QR Code</label>
                <input type="text" wire:model="qrCode" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg"
                       placeholder="Scan atau masukkan kode QR..."
                       autofocus>
                @error('qrCode') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <button wire:click="scanQRCode" 
                    class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                Scan QR Code
            </button>
        </div>
    @else
        <div class="border rounded-lg p-6 bg-blue-50">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Detail Peminjaman</h3>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-xs text-gray-500">Nama Siswa</label>
                    <p class="font-medium">{{ $scannedRequest->user->name }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-500">NIS</label>
                    <p class="font-medium">{{ $scannedRequest->user->nis }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-500">Kelas</label>
                    <p class="font-medium">{{ $scannedRequest->user->kelas }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-500">Jurusan</label>
                    <p class="font-medium">{{ $scannedRequest->user->jurusan }}</p>
                </div>
            </div>

            <div class="border-t pt-4 mt-4">
                <h4 class="font-semibold text-gray-700 mb-2">Barang</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-gray-500">Nama Barang</label>
                        <p class="font-medium">{{ $scannedRequest->item->name }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Jumlah</label>
                        <p class="font-medium">{{ $scannedRequest->quantity }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Tanggal Pinjam</label>
                        <p class="font-medium">{{ $scannedRequest->borrow_date }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Tanggal Kembali</label>
                        <p class="font-medium">{{ $scannedRequest->return_date }}</p>
                    </div>
                </div>
            </div>

            <div class="border-t pt-4 mt-4">
                <div>
                    <label class="text-xs text-gray-500">Keperluan</label>
                    <p class="font-medium">{{ $scannedRequest->purpose }}</p>
                </div>
                @if($scannedRequest->notes)
                    <div class="mt-2">
                        <label class="text-xs text-gray-500">Catatan</label>
                        <p class="text-sm">{{ $scannedRequest->notes }}</p>
                    </div>
                @endif
            </div>

            <div class="border-t pt-4 mt-4">
                <div class="flex items-center gap-2">
                    <label class="text-xs text-gray-500">Status Guru:</label>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-200 text-green-800">
                        {{ $scannedRequest->status_label }}
                    </span>
                </div>
            </div>

            @if($isReturnMode)
                <div class="border-t pt-4 mt-4">
                    <h4 class="font-semibold text-gray-700 mb-2">Kondisi Pengembalian</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi Barang *</label>
                            <select wire:model="returnCondition" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="good">Barang Baik</option>
                                <option value="damaged">Barang Rusak</option>
                                <option value="lost">Barang Hilang</option>
                            </select>
                            @error('returnCondition') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Pengembalian</label>
                            <textarea wire:model="returnNotes" rows="2" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Catatan tambahan..."></textarea>
                            @error('returnNotes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex gap-3 mt-6">
                <button wire:click="cancel" 
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </button>
                @if($isReturnMode)
                    <button wire:click="processReturn" 
                            class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                        Selesaikan Pengembalian
                    </button>
                @else
                    <button wire:click="approveBorrowing" 
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Setujui Pengambilan Barang
                    </button>
                @endif
            </div>
        </div>
    @endif

    @session('success')
        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('success') }}
        </div>
    @endsession

    @session('error')
        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
            {{ session('error') }}
        </div>
    @endsession
</div>
