<div>
<style>
/* ── Inventory Manager Styles ── */
.im-wrap { display: flex; flex-direction: column; gap: 24px; }
.im-alert { display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:12px;background:rgba(52,211,153,.1);border:1px solid rgba(52,211,153,.25);color:#34d399;font-size:13px;font-weight:600;animation:imSlide .3s ease; }
@keyframes imSlide { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:translateY(0)} }
.im-card { background:rgba(15,23,42,.8);border:1px solid rgba(148,163,184,.12);border-radius:20px;padding:28px;backdrop-filter:blur(12px); }
.im-card-header { display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px; }
.im-card-title { font-size:18px;font-weight:800;color:#f1f5f9;margin-bottom:4px; }
.im-card-sub { font-size:13px;color:#94a3b8; }
.im-card-icon { width:46px;height:46px;background:rgba(37,99,235,.15);border:1px solid rgba(37,99,235,.25);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0; }
/* Grid */
.im-grid { display:grid;grid-template-columns:1fr 1fr;gap:18px; }
.im-grid-3 { display:grid;grid-template-columns:1fr 1fr 1fr;gap:18px; }
.im-field { display:flex;flex-direction:column;gap:6px; }
.im-field.span2 { grid-column:1/-1; }
.im-label { font-size:12px;font-weight:700;color:#94a3b8;letter-spacing:.04em;text-transform:uppercase; }
.im-req { color:#ef4444; }
.im-input,.im-select,.im-textarea { background:rgba(255,255,255,.04);border:1.5px solid rgba(148,163,184,.15);border-radius:12px;padding:11px 14px;font-size:14px;color:#e2e8f0;outline:none;width:100%;transition:border-color .2s,box-shadow .2s;font-family:inherit; }
.im-input:focus,.im-select:focus,.im-textarea:focus { border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.15); }
.im-input::placeholder,.im-textarea::placeholder { color:#475569; }
.im-select option { background:#0f172a;color:#e2e8f0; }
.im-textarea { resize:vertical;min-height:80px; }
.im-input[type=file] { padding:8px 14px;cursor:pointer; }
.im-error { font-size:12px;color:#f87171;margin-top:2px; }
/* Section divider */
.im-section { margin-top:24px;padding-top:20px;border-top:1px solid rgba(255,255,255,.06); }
.im-section-title { font-size:13px;font-weight:700;color:#64748b;letter-spacing:.06em;text-transform:uppercase;margin-bottom:16px;display:flex;align-items:center;gap:8px; }
.im-section-title::after { content:'';flex:1;height:1px;background:rgba(255,255,255,.06); }
/* Buttons */
.im-actions { display:flex;align-items:center;gap:10px;margin-top:24px;padding-top:20px;border-top:1px solid rgba(255,255,255,.06); }
.im-btn { display:inline-flex;align-items:center;gap:7px;padding:11px 22px;border-radius:12px;font-size:14px;font-weight:700;cursor:pointer;border:none;transition:all .2s; }
.im-btn-primary { background:#2563eb;color:#fff;box-shadow:0 4px 14px rgba(37,99,235,.35); }
.im-btn-primary:hover { background:#1d4ed8;transform:translateY(-1px);box-shadow:0 6px 20px rgba(37,99,235,.45); }
.im-btn-secondary { background:rgba(255,255,255,.06);color:#94a3b8;border:1px solid rgba(255,255,255,.1); }
.im-btn-secondary:hover { background:rgba(255,255,255,.1);color:#e2e8f0; }
.im-btn-sm { padding:7px 12px;font-size:12px;border-radius:9px; }
.im-btn-edit { background:rgba(59,130,246,.1);color:#60a5fa;border:1px solid rgba(59,130,246,.2); }
.im-btn-edit:hover { background:#3b82f6;color:#fff; }
.im-btn-del { background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.2); }
.im-btn-del:hover { background:#ef4444;color:#fff; }
.im-editing-badge { display:inline-flex;align-items:center;gap:6px;background:rgba(251,191,36,.1);border:1px solid rgba(251,191,36,.2);color:#fbbf24;font-size:12px;font-weight:700;padding:5px 12px;border-radius:999px; }
/* Table */
.im-table-card { background:rgba(15,23,42,.8);border:1px solid rgba(148,163,184,.12);border-radius:20px;overflow:hidden;backdrop-filter:blur(12px); }
.im-table-header { display:flex;align-items:center;justify-content:space-between;padding:18px 24px;border-bottom:1px solid rgba(255,255,255,.06);flex-wrap:wrap;gap:12px; }
.im-table-title { font-size:16px;font-weight:800;color:#f1f5f9; }
.im-count { font-size:12px;background:rgba(37,99,235,.15);color:#60a5fa;border:1px solid rgba(37,99,235,.2);padding:3px 10px;border-radius:999px;font-weight:700; }
.im-search { display:flex;align-items:center;gap:8px;background:rgba(255,255,255,.04);border:1.5px solid rgba(148,163,184,.12);border-radius:10px;padding:8px 12px; }
.im-search input { background:none;border:none;outline:none;font-size:13px;color:#e2e8f0;width:200px; }
.im-search input::placeholder { color:#475569; }
table.im-table { width:100%;border-collapse:collapse; }
table.im-table thead th { padding:12px 18px;font-size:11px;font-weight:700;color:#64748b;letter-spacing:.08em;text-transform:uppercase;background:rgba(255,255,255,.02);text-align:left;border-bottom:1px solid rgba(255,255,255,.05); }
table.im-table tbody tr { border-bottom:1px solid rgba(255,255,255,.04);transition:background .15s; }
table.im-table tbody tr:last-child { border-bottom:none; }
table.im-table tbody tr:hover { background:rgba(255,255,255,.03); }
table.im-table tbody td { padding:13px 18px;font-size:13px;color:#94a3b8;vertical-align:middle; }
.im-item-name { font-size:14px;font-weight:700;color:#f1f5f9;margin-bottom:2px; }
.im-item-code { font-size:11px;color:#475569;font-family:monospace; }
.im-badge { display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:999px;font-size:11px;font-weight:700; }
.badge-tersedia { background:rgba(52,211,153,.12);color:#34d399; }
.badge-dipinjam { background:rgba(248,113,113,.12);color:#f87171; }
.badge-maintenance { background:rgba(251,191,36,.12);color:#fbbf24; }
.badge-hilang { background:rgba(148,163,184,.12);color:#94a3b8; }
.im-cond-dot { width:6px;height:6px;border-radius:50%;display:inline-block;margin-right:5px; }
.im-empty { padding:60px 24px;text-align:center; }
.im-empty-icon { font-size:52px;margin-bottom:14px; }
.im-empty-text { font-size:14px;color:#64748b; }
@media(max-width:900px){.im-grid,.im-grid-3{grid-template-columns:1fr}}
</style>

<div class="im-wrap" wire:key="inventory-manager">

    @if (session()->has('message'))
    <div class="im-alert">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('message') }}
    </div>
    @endif

    {{-- ═══ FORM CARD ═══ --}}
    <div class="im-card">
        <div class="im-card-header">
            <div>
                <div class="im-card-title">{{ $editingId ? 'Edit Barang' : 'Tambah Barang Baru' }}</div>
                <div class="im-card-sub">{{ $editingId ? 'Perbarui data barang inventaris.' : 'Catat barang inventaris sekolah dengan lengkap dan akurat.' }}</div>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
                @if($editingId)
                <div class="im-editing-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Mode Edit
                </div>
                @endif
                <div class="im-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#3b82f6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
        </div>

        <form wire:submit="save">
            {{-- Informasi Dasar --}}
            <div class="im-section-title">Informasi Dasar</div>
            <div class="im-grid">
                <div class="im-field">
                    <label class="im-label">Nama Barang <span class="im-req">*</span></label>
                    <input wire:model="name" type="text" class="im-input" placeholder="Contoh: Proyektor Epson EB-S41">
                    @error('name') <div class="im-error">{{ $message }}</div> @enderror
                </div>
                <div class="im-field">
                    <label class="im-label">Kategori <span class="im-req">*</span></label>
                    <select wire:model="category_id" class="im-select">
                        <option value="">— Pilih Kategori —</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="im-error">{{ $message }}</div> @enderror
                </div>
                <div class="im-field span2">
                    <label class="im-label">Deskripsi</label>
                    <textarea wire:model="description" class="im-textarea" placeholder="Deskripsi singkat barang..."></textarea>
                </div>
            </div>

            {{-- Detail Barang --}}
            <div class="im-section">
                <div class="im-section-title">Detail Barang</div>
                <div class="im-grid">
                    <div class="im-field">
                        <label class="im-label">Merk / Brand</label>
                        <input wire:model="brand" type="text" class="im-input" placeholder="Contoh: Epson, Dell, Yamaha">
                    </div>
                    <div class="im-field">
                        <label class="im-label">Tipe / Model</label>
                        <input wire:model="type" type="text" class="im-input" placeholder="Contoh: EB-S41, Inspiron 15">
                    </div>
                    <div class="im-field">
                        <label class="im-label">Tahun Pengadaan</label>
                        <input wire:model="purchase_year" type="number" class="im-input" placeholder="{{ date('Y') }}" min="2000" max="{{ date('Y') }}">
                    </div>
                    <div class="im-field">
                        <label class="im-label">Harga (Rp)</label>
                        <input wire:model="price" type="number" class="im-input" placeholder="Contoh: 5000000">
                    </div>
                </div>
            </div>

            {{-- Penempatan & Kondisi --}}
            <div class="im-section">
                <div class="im-section-title">Penempatan & Kondisi</div>
                <div class="im-grid">
                    <div class="im-field">
                        <label class="im-label">Lokasi / Ruangan</label>
                        <select wire:model="location_id" class="im-select">
                            <option value="">— Pilih Lokasi —</option>
                            @foreach($locations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->building }}{{ $loc->floor ? ' Lt.'.$loc->floor : '' }} / {{ $loc->room }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="im-field">
                        <label class="im-label">Supplier</label>
                        <select wire:model="supplier_id" class="im-select">
                            <option value="">— Pilih Supplier —</option>
                            @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="im-field">
                        <label class="im-label">Kondisi Barang</label>
                        <select wire:model="condition" class="im-select">
                            <option value="Baik">✅ Baik</option>
                            <option value="Rusak Ringan">⚠️ Rusak Ringan</option>
                            <option value="Rusak Berat">❌ Rusak Berat</option>
                        </select>
                    </div>
                    <div class="im-field">
                        <label class="im-label">Status</label>
                        <select wire:model="status" class="im-select">
                            <option value="Tersedia">🟢 Tersedia</option>
                            <option value="Dipinjam">🔴 Dipinjam</option>
                            <option value="Maintenance">🟡 Maintenance</option>
                            <option value="Hilang">⚫ Hilang</option>
                        </select>
                    </div>
                    <div class="im-field">
                        <label class="im-label">Jumlah / Stok</label>
                        <input wire:model="stock" type="number" class="im-input" placeholder="1" min="0">
                    </div>
                    <div class="im-field">
                        <label class="im-label">Foto Barang</label>
                        <input wire:model="photo" type="file" class="im-input" accept="image/*">
                        @error('photo') <div class="im-error">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="im-actions">
                <button type="submit" class="im-btn im-btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        @if($editingId)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        @endif
                    </svg>
                    {{ $editingId ? 'Simpan Perubahan' : 'Tambah Barang' }}
                </button>
                <button type="button" wire:click="resetForm" class="im-btn im-btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Reset
                </button>
                <div style="margin-left:auto;font-size:12px;color:#475569">
                    <span style="color:#ef4444">*</span> Wajib diisi
                </div>
            </div>
        </form>
    </div>

    {{-- ═══ TABLE CARD ═══ --}}
    <div class="im-table-card">
        <div class="im-table-header">
            <div style="display:flex;align-items:center;gap:12px">
                <div class="im-table-title">Daftar Barang Inventaris</div>
                <span class="im-count">{{ $items->count() }} barang</span>
            </div>
            <div class="im-search">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;color:#475569;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama barang, kode...">
            </div>
        </div>

        @if($items->isEmpty())
        <div class="im-empty">
            <div class="im-empty-icon">📦</div>
            <div class="im-empty-text">Belum ada barang. Tambahkan barang pertama di atas.</div>
        </div>
        @else
        <div style="overflow-x:auto">
            <table class="im-table">
                <thead>
                    <tr>
                        <th style="width:36px">#</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $i => $item)
                    <tr wire:key="item-{{ $item->id }}">
                        <td style="color:#475569;font-size:12px">{{ $i + 1 }}</td>
                        <td>
                            <div class="im-item-name">{{ $item->name }}</div>
                            <div class="im-item-code">{{ $item->inventory_number }}</div>
                        </td>
                        <td>
                            @if($item->category)
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:13px;color:#cbd5e1">
                                <span style="font-size:15px">{{ $item->category->icon ?: '🗂️' }}</span>
                                {{ $item->category->name }}
                            </span>
                            @else
                            <span style="color:#475569">—</span>
                            @endif
                        </td>
                        <td style="font-size:12px">{{ $item->location?->building ?? '—' }}</td>
                        <td>
                            @php
                            $condColors = ['Baik'=>'#34d399','Rusak Ringan'=>'#fbbf24','Rusak Berat'=>'#f87171'];
                            $condColor  = $condColors[$item->condition] ?? '#94a3b8';
                            @endphp
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;color:{{ $condColor }}">
                                <span class="im-cond-dot" style="background:{{ $condColor }}"></span>
                                {{ $item->condition }}
                            </span>
                        </td>
                        <td>
                            @php
                            $badgeClass = match($item->status) {
                                'Tersedia'    => 'badge-tersedia',
                                'Dipinjam'    => 'badge-dipinjam',
                                'Maintenance' => 'badge-maintenance',
                                default       => 'badge-hilang',
                            };
                            @endphp
                            <span class="im-badge {{ $badgeClass }}">
                                <span style="width:5px;height:5px;border-radius:50%;background:currentColor"></span>
                                {{ $item->status }}
                            </span>
                        </td>
                        <td style="font-weight:700;color:#60a5fa">{{ $item->stock }}</td>
                        <td>
                            <div style="display:flex;gap:7px">
                                <button wire:click="edit({{ $item->id }})" class="im-btn im-btn-sm im-btn-edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </button>
                                <button wire:click="delete({{ $item->id }})"
                                    wire:confirm="Hapus barang '{{ $item->name }}'?"
                                    class="im-btn im-btn-sm im-btn-del">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if(method_exists($items, 'links'))
        <div style="padding:16px 20px;border-top:1px solid rgba(255,255,255,.06)">
            {{ $items->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
</div>
