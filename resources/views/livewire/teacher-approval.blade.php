<div class="bg-white rounded-xl shadow-lg p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Persetujuan Peminjaman</h2>

    @if($requests->isEmpty())
        <div class="text-center py-12 text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-lg">Tidak ada permintaan peminjaman</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($requests as $request)
                <div class="border rounded-lg p-4 @if($request->status === 'pending') border-yellow-200 bg-yellow-50 @elseif($request->status === 'approved') border-green-200 bg-green-50 @else border-red-200 bg-red-50 @endif">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-bold text-gray-800">{{ $request->user->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $request->user->kelas }} - {{ $request->user->jurusan }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            @if($request->status === 'pending') bg-yellow-200 text-yellow-800
                            @elseif($request->status === 'approved') bg-green-200 text-green-800
                            @else bg-red-200 text-red-800 @endif">
                            {{ $request->status_label }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="text-xs text-gray-500">Barang</label>
                            <p class="font-medium">{{ $request->item->name }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Jumlah</label>
                            <p class="font-medium">{{ $request->quantity }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Keperluan</label>
                            <p class="font-medium">{{ $request->purpose }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Tanggal</label>
                            <p class="font-medium">{{ $request->borrow_date }} - {{ $request->return_date }}</p>
                        </div>
                    </div>

                    @if($request->notes)
                        <div class="mb-4">
                            <label class="text-xs text-gray-500">Catatan</label>
                            <p class="text-sm">{{ $request->notes }}</p>
                        </div>
                    @endif

                    @if($request->status === 'pending')
                        <div class="flex gap-3">
                            <button wire:click="approve({{ $request->id }})" 
                                    class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                                ✅ Setujui
                            </button>
                            <button wire:click="showRejectModal({{ $request->id }})" 
                                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                                ❌ Tolak
                            </button>
                        </div>
                    @elseif($request->status === 'rejected' && $request->rejection_reason)
                        <div class="bg-red-100 p-3 rounded-lg">
                            <p class="text-sm text-red-800"><strong>Alasan Penolakan:</strong> {{ $request->rejection_reason }}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- Reject Modal --}}
    @if($selectedRequestId)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <h3 class="text-lg font-bold mb-4">Tolak Permintaan Peminjaman</h3>
                <textarea wire:model="rejectionReason" rows="4" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent mb-4"
                          placeholder="Masukkan alasan penolakan..."></textarea>
                <div class="flex gap-3">
                    <button wire:click="$set('selectedRequestId', null)" 
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button wire:click="reject" 
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                        Tolak
                    </button>
                </div>
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
