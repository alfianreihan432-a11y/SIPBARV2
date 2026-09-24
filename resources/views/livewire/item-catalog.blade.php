<div>
    {{-- Header --}}
    <div class="page-header">
        <div class="page-header-left">
            <div class="page-title">
                Katalog Barang
                @if($items->total() > 0)
                <span class="page-title-count">{{ $items->total() }} item</span>
                @endif
            </div>
            <div class="page-subtitle">Pilih barang yang ingin kamu pinjam dari inventaris sekolah</div>
        </div>
    </div>

    {{-- Search + Filter --}}
    <div class="s-card s-card--flat" style="margin-bottom:20px">
        <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
            <div style="flex:1;min-width:200px">
                <div class="s-filter-label" style="margin-bottom:6px">Cari Barang</div>
                <div style="position:relative">
                    <svg xmlns="http://www.w3.org/2000/svg" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:var(--subtle)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        style="width:100%;padding:9px 12px 9px 38px;background:var(--input-bg);border:1px solid var(--border2);border-radius:10px;font-size:13px;color:var(--text);font-family:var(--font-body);outline:none;transition:border-color .15s"
                        placeholder="Nama barang, kode, atau deskripsi...">
                </div>
            </div>
            <div style="min-width:180px">
                <div class="s-filter-label" style="margin-bottom:6px">Kategori</div>
                <select wire:model.live="categoryFilter"
                    style="width:100%;padding:9px 12px;background:var(--input-bg);border:1px solid var(--border2);border-radius:10px;font-size:13px;color:var(--text);font-family:var(--font-body);outline:none">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Items Grid --}}
    @if($items->count() > 0)
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;margin-bottom:24px">
            @foreach($items as $item)
            @php
                $status = $item->getCatalogStatusInfo();
            @endphp
            <div style="background:var(--card);border-radius:16px;border:1px solid var(--border2);overflow:hidden;transition:all .2s;display:flex;flex-direction:column"
                 onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.1)'"
                 onmouseout="this.style.transform='';this.style.boxShadow=''">
                {{-- Image --}}
                <div style="aspect-ratio:4/3;background:var(--bg3);position:relative;overflow:hidden">
                    @if($item->photo_path)
                        <img src="{{ asset('storage/' . $item->photo_path) }}" alt="{{ $item->name }}"
                             style="width:100%;height:100%;object-fit:cover">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:40px;height:40px;color:var(--subtle)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif

                    {{-- Dynamic Status badge --}}
                    <div style="position:absolute;top:10px;right:10px;z-index:2">
                        @if($status['status'] === 'tersedia')
                            {{-- 1. TERSEDIA (Hijau) --}}
                            <span style="padding:4px 10px;background:#10b981;color:#ffffff;font-size:11px;font-weight:700;border-radius:999px;backdrop-filter:blur(4px);box-shadow:0 2px 6px rgba(16,185,129,0.3);display:inline-flex;align-items:center;gap:4px">
                                <span style="width:6px;height:6px;background:#ffffff;border-radius:50%;display:inline-block"></span>
                                {{ $status['badge_label'] }}
                            </span>
                        @elseif($status['status'] === 'menunggu')
                            {{-- 2. MENUNGGU (Kuning / Orange Muda) --}}
                            <span style="padding:4px 10px;background:#f59e0b;color:#ffffff;font-size:11px;font-weight:700;border-radius:999px;backdrop-filter:blur(4px);box-shadow:0 2px 6px rgba(245,158,11,0.3);display:inline-flex;align-items:center;gap:4px">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $status['badge_label'] }}
                            </span>
                        @else
                            {{-- 3. DIPINJAM (Orange Solid) --}}
                            <span style="padding:4px 10px;background:#ea580c;color:#ffffff;font-size:11px;font-weight:700;border-radius:999px;backdrop-filter:blur(4px);box-shadow:0 2px 6px rgba(234,88,12,0.3);display:inline-flex;align-items:center;gap:4px">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                {{ $status['badge_label'] }}
                            </span>
                        @endif
                    </div>

                    {{-- Category badge --}}
                    <div style="position:absolute;top:10px;left:10px;z-index:2">
                        <span style="padding:3px 8px;background:rgba(0,0,0,.6);color:#fff;font-size:10px;font-weight:600;border-radius:5px;letter-spacing:.03em;backdrop-filter:blur(4px)">
                            {{ $item->category->name ?? 'Umum' }}
                        </span>
                    </div>
                </div>

                {{-- Body --}}
                <div style="padding:16px;flex:1;display:flex;flex-direction:column;gap:6px">
                    <div style="font-family:var(--font-head);font-size:14px;font-weight:700;color:var(--text);line-height:1.3">{{ $item->name }}</div>
                    <div style="font-size:11.5px;color:var(--subtle)">{{ $item->code }}</div>
                    <div style="display:flex;align-items:center;gap:8px;margin-top:2px;flex-wrap:wrap">
                        <span style="font-size:11px;color:var(--muted);background:var(--bg3);border:1px solid var(--border2);padding:2px 8px;border-radius:5px">
                            Kondisi: {{ $item->condition }}
                        </span>
                        @if($status['total_stock'] > 1)
                        <span style="font-size:11px;color:var(--muted);background:var(--bg3);border:1px solid var(--border2);padding:2px 8px;border-radius:5px">
                            Total Stok: {{ $status['total_stock'] }} unit
                        </span>
                        @endif
                    </div>
                    @if($item->description)
                    <div style="font-size:12px;color:var(--muted);margin-top:2px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;line-height:1.5">
                        {{ $item->description }}
                    </div>
                    @endif
                    <div style="margin-top:auto;padding-top:12px">
                        @if($status['status'] === 'tersedia')
                            {{-- Tombol Aktif Biru --}}
                            <button wire:click="addToCart({{ $item->id }})"
                                    class="s-btn s-btn--primary" style="width:100%;justify-content:center">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Pinjam Barang
                            </button>
                        @elseif($status['status'] === 'menunggu')
                            {{-- Tombol Disabled Abu-abu --}}
                            <button disabled class="s-btn" style="width:100%;justify-content:center;background:var(--bg3);color:var(--subtle);cursor:not-allowed;border:1px solid var(--border2);opacity:0.85">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Pinjam Barang
                            </button>
                        @else
                            {{-- Tombol Disabled Orange Solid (konsisten dengan badge Dipinjam) --}}
                            <button disabled class="s-btn" style="width:100%;justify-content:center;background:#ea580c;color:#ffffff;cursor:not-allowed;border:1px solid #ea580c;opacity:0.9">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Pinjam Barang
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        {{-- Pagination --}}
        <div style="margin-top:8px">{{ $items->links() }}</div>
    @else
        <div class="s-card">
            <div class="s-empty">
                <div class="s-empty-icon-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:32px;height:32px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div class="s-empty-title">Barang tidak ditemukan</div>
                <div class="s-empty-sub">Coba ubah kata kunci atau pilih kategori lain</div>
                <button wire:click="$set('search','')" wire:click="$set('categoryFilter','')" class="s-btn s-btn--secondary">Reset Pencarian</button>
            </div>
        </div>
    @endif

</div>
