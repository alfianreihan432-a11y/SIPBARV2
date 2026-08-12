<div>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Persetujuan Peminjaman</h2>

    @if($requests->isEmpty())
        <div class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-lg text-gray-500 dark:text-gray-400">Tidak ada permintaan peminjaman</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($requests as $request)
                {{-- Base Card with Solid Colors (No Gradient) --}}
                <div class="bg-white dark:bg-slate-800 border-2 rounded-xl shadow-sm hover:shadow-md transition-shadow
                    @if($request->status === 'pending') border-l-4 border-l-amber-500 @elseif($request->status === 'approved') border-l-4 border-l-emerald-500 @else border-l-4 border-l-red-500 @endif
                    @if($request->status === 'pending') border-amber-200 dark:border-amber-900 @elseif($request->status === 'approved') border-emerald-200 dark:border-emerald-900 @else border-red-200 dark:border-red-900 @endif">
                    
                    {{-- Header: Nama & Status --}}
                    <div class="p-5 border-b border-gray-100 dark:border-slate-700">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ $request->user->name }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $request->user->kelas }} - {{ $request->user->jurusan }}</p>
                            </div>
                            {{-- Status Badge with Solid Color --}}
                            <span class="px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm
                                @if($request->status === 'pending') bg-amber-100 text-amber-800 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800
                                @elseif($request->status === 'approved') bg-emerald-100 text-emerald-800 border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-800
                                @else bg-red-100 text-red-800 border border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800 @endif">
                                {{ $request->status_label }}
                            </span>
                        </div>
                    </div>

                    {{-- Detail Information with High Contrast Text --}}
                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Barang</label>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $request->item->name }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Jumlah</label>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $request->quantity }} unit</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Keperluan</label>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $request->purpose }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Tanggal</label>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $request->borrow_date }} - {{ $request->return_date }}</p>
                        </div>
                    </div>

                    @if($request->notes)
                        <div class="px-5 pb-5">
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Catatan</label>
                            <p class="mt-1 text-sm text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-slate-700/50 p-3 rounded-lg">{{ $request->notes }}</p>
                        </div>
                    @endif

                    @if($request->status === 'pending')
                        {{-- Action Buttons --}}
                        <div class="p-5 bg-gray-50 dark:bg-slate-700/30 border-t border-gray-100 dark:border-slate-700 flex gap-3">
                            <button wire:click="approve({{ $request->id }})" 
                                    class="flex-1 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-lg transition-colors font-semibold shadow-sm flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Setujui
                            </button>
                            <button wire:click="showRejectModal({{ $request->id }})" 
                                    class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white rounded-lg transition-colors font-semibold shadow-sm flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                Tolak
                            </button>
                        </div>
                    @elseif($request->status === 'rejected' && $request->rejection_reason)
                        <div class="m-5 mt-0 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4 rounded-lg">
                            <p class="text-sm text-red-800 dark:text-red-200">
                                <strong class="font-semibold">Alasan Penolakan:</strong> {{ $request->rejection_reason }}
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- Reject Modal --}}
    @if($selectedRequestId)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" wire:click="$set('selectedRequestId', null)">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl max-w-md w-full" wire:click.stop>
                <div class="p-6 border-b border-gray-200 dark:border-slate-700">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Tolak Permintaan Peminjaman</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Berikan alasan penolakan yang jelas kepada siswa</p>
                </div>
                <div class="p-6">
                    <textarea wire:model="rejectionReason" rows="4" 
                              class="w-full px-4 py-3 border-2 border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:focus:ring-red-400 resize-none transition-colors"
                              placeholder="Contoh: Barang sedang dipinjam oleh kelas lain..."></textarea>
                </div>
                <div class="p-6 pt-0 flex gap-3">
                    <button wire:click="$set('selectedRequestId', null)" 
                            class="flex-1 px-4 py-2.5 border-2 border-gray-300 dark:border-slate-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors font-semibold">
                        Batal
                    </button>
                    <button wire:click="reject" 
                            class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white rounded-lg transition-colors font-semibold shadow-sm">
                        Tolak Permintaan
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Success/Error Alerts --}}
    @session('success')
        <div class="fixed bottom-6 right-6 max-w-md bg-emerald-50 dark:bg-emerald-900/30 border-l-4 border-emerald-500 p-4 rounded-lg shadow-lg z-50 animate-slide-up">
            <div class="flex items-start">
                <svg class="h-6 w-6 text-emerald-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ session('success') }}</p>
            </div>
        </div>
    @endsession

    @session('error')
        <div class="fixed bottom-6 right-6 max-w-md bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 p-4 rounded-lg shadow-lg z-50 animate-slide-up">
            <div class="flex items-start">
                <svg class="h-6 w-6 text-red-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
            </div>
        </div>
    @endsession
</div>
