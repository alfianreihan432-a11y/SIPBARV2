<div>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Persetujuan Peminjaman</h2>

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

    @if($pendingRequests->isEmpty())
        <div class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-lg text-gray-500 dark:text-gray-400">Tidak ada permintaan peminjaman yang menunggu persetujuan</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($pendingRequests as $request)
                {{-- Base Card with Solid Colors (No Gradient) --}}
                <div class="bg-white dark:bg-slate-800 border-2 border-l-4 border-l-amber-500 border-amber-200 dark:border-amber-900 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    
                    {{-- Header: Nama & Status --}}
                    <div class="p-5 border-b border-gray-100 dark:border-slate-700">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ $request->user->name ?? 'N/A' }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $request->user->kelas ?? 'N/A' }} - {{ $request->user->jurusan ?? 'N/A' }}</p>
                            </div>
                            {{-- Status Badge --}}
                            <span class="px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm bg-amber-100 text-amber-800 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800">
                                Menunggu Persetujuan
                            </span>
                        </div>
                    </div>

                    {{-- Detail Information with High Contrast Text --}}
                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Barang</label>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $request->item->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Jumlah</label>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $request->quantity }} unit</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Stok Tersedia</label>
                            <p class="mt-1 font-semibold {{ $request->item && $request->item->available_stock >= $request->quantity ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $request->item->available_stock ?? 'N/A' }} unit
                            </p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Tanggal Peminjaman</label>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($request->borrow_date)->format('d M Y') }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Tanggal Pengembalian</label>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($request->return_date)->format('d M Y') }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Keperluan</label>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $request->purpose ?? '-' }}</p>
                        </div>
                    </div>

                    @if($request->notes)
                        <div class="px-5 pb-5">
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Catatan</label>
                            <p class="mt-1 text-sm text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-slate-700/50 p-3 rounded-lg">{{ $request->notes }}</p>
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="p-5 bg-gray-50 dark:bg-slate-700/30 border-t border-gray-100 dark:border-slate-700 flex gap-3">
                        {{-- Approve Form --}}
                        <form action="{{ route('teacher.requests.approve', $request->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-lg transition-colors font-semibold shadow-sm flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Setujui
                            </button>
                        </form>

                        {{-- Reject Button - Opens Modal --}}
                        <button wire:click="openRejectModal({{ $request->id }})" 
                                class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white rounded-lg transition-colors font-semibold shadow-sm flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Tolak
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Reject Modal --}}
    @if($showRejectModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" wire:click="closeRejectModal">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl max-w-md w-full" wire:click.stop>
                <form action="{{ route('teacher.requests.reject', $selectedRequestId) }}" method="POST">
                    @csrf
                    <div class="p-6 border-b border-gray-200 dark:border-slate-700">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Tolak Permintaan Peminjaman</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Berikan alasan penolakan yang jelas kepada siswa (minimal 10 karakter)</p>
                    </div>
                    <div class="p-6">
                        <textarea name="rejection_reason" 
                                  wire:model="rejectionReason"
                                  rows="4" 
                                  required
                                  minlength="10"
                                  maxlength="500"
                                  class="w-full px-4 py-3 border-2 border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:focus:ring-red-400 resize-none transition-colors"
                                  placeholder="Contoh: Barang sedang dipinjam oleh kelas lain dan belum tersedia untuk tanggal yang diminta..."></textarea>
                        @error('rejection_reason')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="p-6 pt-0 flex gap-3">
                        <button type="button" 
                                wire:click="closeRejectModal"
                                class="flex-1 px-4 py-2.5 border-2 border-gray-300 dark:border-slate-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors font-semibold">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white rounded-lg transition-colors font-semibold shadow-sm">
                            Tolak Permintaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
