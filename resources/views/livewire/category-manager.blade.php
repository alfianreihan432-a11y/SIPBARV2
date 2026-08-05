<div>
<style>
/* ── Category Manager Styles ── */
.cm-wrap { display: flex; flex-direction: column; gap: 24px; }

/* Alert */
.cm-alert {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 16px; border-radius: 12px;
    background: rgba(52,211,153,.1); border: 1px solid rgba(52,211,153,.25);
    color: #34d399; font-size: 13px; font-weight: 600;
    animation: slideIn .3s ease;
}
.cm-alert-err { background: rgba(239,68,68,.1); border-color: rgba(239,68,68,.25); color: #f87171; }
@keyframes slideIn { from { opacity:0; transform:translateY(-8px) } to { opacity:1; transform:translateY(0) } }

/* Form Card */
.cm-form-card {
    background: rgba(15,23,42,.8);
    border: 1px solid rgba(148,163,184,.12);
    border-radius: 20px;
    padding: 28px;
    backdrop-filter: blur(12px);
}
.cm-form-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; }
.cm-form-title { font-size: 18px; font-weight: 800; color: #f1f5f9; margin-bottom: 4px; }
.cm-form-sub { font-size: 13px; color: #94a3b8; }
.cm-form-icon {
    width: 46px; height: 46px; background: rgba(37,99,235,.15);
    border: 1px solid rgba(37,99,235,.25); border-radius: 14px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}

/* Form Grid */
.cm-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.cm-field { display: flex; flex-direction: column; gap: 6px; }
.cm-field.full { grid-column: 1 / -1; }
.cm-label { font-size: 12px; font-weight: 700; color: #94a3b8; letter-spacing: .04em; text-transform: uppercase; }
.cm-input, .cm-select, .cm-textarea {
    background: rgba(255,255,255,.04);
    border: 1.5px solid rgba(148,163,184,.15);
    border-radius: 12px;
    padding: 11px 14px;
    font-size: 14px; color: #e2e8f0;
    outline: none; width: 100%;
    transition: border-color .2s, box-shadow .2s;
    font-family: inherit;
}
.cm-input:focus, .cm-select:focus, .cm-textarea:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.15);
}
.cm-input::placeholder, .cm-textarea::placeholder { color: #475569; }
.cm-select option { background: #0f172a; color: #e2e8f0; }
.cm-textarea { resize: vertical; min-height: 90px; }
.cm-color-row { display: flex; align-items: center; gap: 10px; }
.cm-color-input { width: 52px; height: 44px; padding: 4px; border-radius: 10px; border: 1.5px solid rgba(148,163,184,.15); background: rgba(255,255,255,.04); cursor: pointer; }
.cm-color-preview {
    flex: 1; height: 44px; border-radius: 10px;
    display: flex; align-items: center; padding: 0 14px;
    font-size: 13px; font-weight: 600; color: #fff;
    border: 1.5px solid rgba(255,255,255,.1);
}
.cm-error { font-size: 12px; color: #f87171; margin-top: 2px; }

/* Buttons */
.cm-actions { display: flex; align-items: center; gap: 10px; margin-top: 24px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,.06); }
.cm-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 11px 22px; border-radius: 12px; font-size: 14px; font-weight: 700;
    cursor: pointer; border: none; transition: all .2s; text-decoration: none;
}
.cm-btn-primary { background: #2563eb; color: #fff; box-shadow: 0 4px 14px rgba(37,99,235,.35); }
.cm-btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(37,99,235,.45); }
.cm-btn-secondary { background: rgba(255,255,255,.06); color: #94a3b8; border: 1px solid rgba(255,255,255,.1); }
.cm-btn-secondary:hover { background: rgba(255,255,255,.1); color: #e2e8f0; }
.cm-btn-danger { background: rgba(239,68,68,.1); color: #f87171; border: 1px solid rgba(239,68,68,.2); padding: 8px 14px; font-size: 13px; border-radius: 10px; }
.cm-btn-danger:hover { background: #ef4444; color: #fff; }
.cm-btn-edit { background: rgba(59,130,246,.1); color: #60a5fa; border: 1px solid rgba(59,130,246,.2); padding: 8px 14px; font-size: 13px; border-radius: 10px; }
.cm-btn-edit:hover { background: #3b82f6; color: #fff; }
.cm-editing-badge { display: inline-flex; align-items: center; gap: 6px; background: rgba(251,191,36,.1); border: 1px solid rgba(251,191,36,.2); color: #fbbf24; font-size: 12px; font-weight: 700; padding: 5px 12px; border-radius: 999px; }

/* Table Card */
.cm-table-card {
    background: rgba(15,23,42,.8);
    border: 1px solid rgba(148,163,184,.12);
    border-radius: 20px; overflow: hidden;
    backdrop-filter: blur(12px);
}
.cm-table-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 20px 24px; border-bottom: 1px solid rgba(255,255,255,.06);
}
.cm-table-title { font-size: 16px; font-weight: 800; color: #f1f5f9; }
.cm-count { font-size: 12px; background: rgba(37,99,235,.15); color: #60a5fa; border: 1px solid rgba(37,99,235,.2); padding: 3px 10px; border-radius: 999px; font-weight: 700; }
.cm-table-search { display: flex; align-items: center; gap: 8px; background: rgba(255,255,255,.04); border: 1.5px solid rgba(148,163,184,.12); border-radius: 10px; padding: 8px 12px; }
.cm-table-search input { background: none; border: none; outline: none; font-size: 13px; color: #e2e8f0; width: 200px; }
.cm-table-search input::placeholder { color: #475569; }
.cm-empty { padding: 60px 24px; text-align: center; }
.cm-empty-icon { font-size: 48px; margin-bottom: 14px; }
.cm-empty-text { font-size: 14px; color: #64748b; }

table.cm-table { width: 100%; border-collapse: collapse; }
table.cm-table thead th {
    padding: 12px 20px; font-size: 11px; font-weight: 700;
    color: #64748b; letter-spacing: .08em; text-transform: uppercase;
    background: rgba(255,255,255,.02); text-align: left;
    border-bottom: 1px solid rgba(255,255,255,.05);
}
table.cm-table tbody tr { border-bottom: 1px solid rgba(255,255,255,.04); transition: background .15s; }
table.cm-table tbody tr:last-child { border-bottom: none; }
table.cm-table tbody tr:hover { background: rgba(255,255,255,.03); }
table.cm-table tbody td { padding: 14px 20px; font-size: 14px; color: #94a3b8; vertical-align: middle; }
.cm-cat-name { font-size: 14px; font-weight: 700; color: #f1f5f9; display: flex; align-items: center; gap: 8px; }
.cm-color-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 12px; border-radius: 999px;
    font-size: 12px; font-weight: 700;
}
.cm-color-dot { width: 8px; height: 8px; border-radius: 50%; }
.cm-actions-cell { display: flex; gap: 8px; align-items: center; }
@media (max-width: 768px) { .cm-grid { grid-template-columns: 1fr; } }
</style>

<div class="cm-wrap" wire:key="category-manager">

    @if (session()->has('message'))
    <div class="cm-alert">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('message') }}
    </div>
    @endif

    {{-- ═══ FORM CARD ═══ --}}
    <div class="cm-form-card">
        <div class="cm-form-header">
            <div>
                <div class="cm-form-title">
                    {{ $editingId ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
                </div>
                <div class="cm-form-sub">
                    {{ $editingId ? 'Perbarui data kategori inventaris.' : 'Tambahkan kategori baru untuk mengelompokkan barang inventaris.' }}
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
                @if($editingId)
                <div class="cm-editing-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Mode Edit
                </div>
                @endif
                <div class="cm-form-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#3b82f6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
            </div>
        </div>

        <form wire:submit.prevent="save">
            <div class="cm-grid">
                {{-- Nama --}}
                <div class="cm-field">
                    <label class="cm-label">Nama Kategori <span style="color:#ef4444">*</span></label>
                    <input wire:model="name" type="text" class="cm-input" placeholder="Contoh: Elektronik, Furnitur...">
                    @error('name') <div class="cm-error">{{ $message }}</div> @enderror
                </div>

                {{-- Ikon --}}
                <div class="cm-field">
                    <label class="cm-label">Ikon / Emoji</label>
                    <input wire:model="icon" type="text" class="cm-input" placeholder="Contoh: 💻 📚 🔬">
                    @error('icon') <div class="cm-error">{{ $message }}</div> @enderror
                </div>

                {{-- Warna --}}
                <div class="cm-field">
                    <label class="cm-label">Warna Kategori</label>
                    <div class="cm-color-row">
                        <input wire:model.live="color" type="color" class="cm-color-input" value="{{ $color ?? '#3b82f6' }}">
                        <div class="cm-color-preview" style="background:{{ $color ?? '#3b82f6' }}1a;border-color:{{ $color ?? '#3b82f6' }}40;color:{{ $color ?? '#3b82f6' }}">
                            <span style="width:10px;height:10px;border-radius:50%;background:{{ $color ?? '#3b82f6' }};display:inline-block;margin-right:8px"></span>
                            {{ $color ?? '#3b82f6' }}
                        </div>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="cm-field">
                    <label class="cm-label">Deskripsi</label>
                    <textarea wire:model="description" class="cm-textarea" placeholder="Deskripsi singkat kategori ini..." rows="3"></textarea>
                    @error('description') <div class="cm-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="cm-actions">
                <button type="submit" class="cm-btn cm-btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        @if($editingId)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        @endif
                    </svg>
                    {{ $editingId ? 'Simpan Perubahan' : 'Tambah Kategori' }}
                </button>
                <button type="button" wire:click="resetForm" class="cm-btn cm-btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Reset
                </button>
                <div style="margin-left:auto;font-size:12px;color:#475569;display:flex;align-items:center;gap:5px">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;color:#64748b" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Tanda <span style="color:#ef4444">*</span> wajib diisi
                </div>
            </div>
        </form>
    </div>

    {{-- ═══ TABLE CARD ═══ --}}
    <div class="cm-table-card">
        <div class="cm-table-header">
            <div style="display:flex;align-items:center;gap:12px">
                <div class="cm-table-title">Daftar Kategori</div>
                <span class="cm-count">{{ $categories->count() }} kategori</span>
            </div>
            <div class="cm-table-search">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;color:#475569;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari kategori...">
            </div>
        </div>

        @if($categories->isEmpty())
        <div class="cm-empty">
            <div class="cm-empty-icon">🗂️</div>
            <div class="cm-empty-text">Belum ada kategori. Tambahkan kategori pertama di atas.</div>
        </div>
        @else
        <div style="overflow-x:auto">
            <table class="cm-table">
                <thead>
                    <tr>
                        <th style="width:40px">#</th>
                        <th>Nama & Ikon</th>
                        <th>Deskripsi</th>
                        <th>Warna</th>
                        <th>Jumlah Barang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $i => $cat)
                    <tr wire:key="cat-{{ $cat->id }}">
                        <td style="color:#475569;font-size:12px">{{ $i + 1 }}</td>
                        <td>
                            <div class="cm-cat-name">
                                <span style="font-size:20px">{{ $cat->icon ?: '🗂️' }}</span>
                                {{ $cat->name }}
                            </div>
                        </td>
                        <td style="max-width:220px">
                            <span style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;font-size:13px;line-height:1.5">
                                {{ $cat->description ?: '—' }}
                            </span>
                        </td>
                        <td>
                            <span class="cm-color-badge" style="background:{{ $cat->color ?? '#3b82f6' }}1a;color:{{ $cat->color ?? '#3b82f6' }}">
                                <span class="cm-color-dot" style="background:{{ $cat->color ?? '#3b82f6' }}"></span>
                                {{ $cat->color ?? '#3b82f6' }}
                            </span>
                        </td>
                        <td>
                            <span style="font-size:13px;font-weight:700;color:#60a5fa">
                                {{ $cat->items_count ?? ($cat->items ? $cat->items->count() : 0) }} barang
                            </span>
                        </td>
                        <td>
                            <div class="cm-actions-cell">
                                <button wire:click="edit({{ $cat->id }})" class="cm-btn cm-btn-edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </button>
                                <button wire:click="delete({{ $cat->id }})"
                                    wire:confirm="Hapus kategori '{{ $cat->name }}'? Aksi ini tidak dapat dibatalkan."
                                    class="cm-btn cm-btn-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
</div>
