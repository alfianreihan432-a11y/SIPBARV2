@extends('layouts.guru')

@section('title', 'Ajukan Peminjaman')
@section('page-heading', 'Ajukan Peminjaman')

@section('content')
<style>
    .form-card {
        max-width: 700px;
        margin: 20px auto;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 28px 24px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        color: var(--text);
    }
    .form-header {
        margin-bottom: 24px;
    }
    .form-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--text);
    }
    .form-desc {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 6px;
    }
    .form-input {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        background: var(--input-bg);
        color: var(--text);
        outline: none;
        transition: border-color 0.2s;
    }
    .form-input:focus {
        border-color: var(--accent);
    }
    .form-select {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        background: var(--input-bg) !important;
        color: var(--text) !important;
        outline: none;
        transition: border-color 0.2s;
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        color-scheme: light;
        background-image: none !important;
        position: relative;
    }
    .form-select::-ms-expand { display: none; }
    .form-select::-webkit-select-dropdown-icon { display: none; }
    html.dark .form-select {
        color: var(--text) !important;
        background: var(--input-bg) !important;
        color-scheme: dark;
    }
    .form-select:focus {
        border-color: var(--accent);
        color: var(--text) !important;
    }
    .form-textarea {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        background: var(--input-bg);
        color: var(--text);
        outline: none;
        transition: border-color 0.2s;
        resize: vertical;
        min-height: 100px;
    }
    .form-textarea:focus {
        border-color: var(--accent);
    }
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }
    .form-hint {
        font-size: 12px;
        color: var(--muted);
        margin-top: 4px;
    }
    .error-message {
        color: #ef4444;
        font-size: 12px;
        margin-top: 4px;
    }
    
    .submit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 14px 28px;
        background: var(--accent);
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        width: 100%;
    }
    .submit-btn:hover {
        background: #059669;
    }
    .cancel-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 14px 28px;
        background: var(--bg3);
        color: var(--text);
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none;
        width: 100%;
    }
    .cancel-btn:hover {
        background: var(--border);
    }
    
    .form-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 24px;
    }
    
    .item-info {
        background: var(--bg3);
        border-radius: 8px;
        padding: 12px;
        margin-top: 8px;
        font-size: 13px;
        color: var(--muted);
    }
    .item-info-row {
        display: flex;
        justify-content: space-between;
        padding: 4px 0;
    }
</style>

<div class="form-card">
    <div class="form-header">
        <h2 class="form-title">Ajukan Peminjaman Barang</h2>
        <p class="form-desc">Isi formulir di bawah ini untuk mengajukan peminjaman barang. Permohonan akan disetujui oleh Kepala Jurusan.</p>
    </div>
    
    <form method="POST" action="{{ route('teacher.peminjaman-guru.store') }}">
        @csrf
        
        <div class="form-group">
            <label class="form-label">Pilih Kepala Jurusan *</label>
            <select name="kepala_jurusan_id" class="form-select" required>
                <option value="">Pilih Kepala Jurusan...</option>
                @foreach($kepalaJurusans as $kajur)
                    <option value="{{ $kajur->id }}"
                            {{ $currentUserJurusan && $kajur->jurusan && $currentUserJurusan->id === $kajur->jurusan->id ? 'selected' : '' }}>
                        {{ $kajur->name }} ({{ $kajur->jurusan ? $kajur->jurusan->nama : 'Jurusan tidak ditentukan' }})
                    </option>
                @endforeach
            </select>
            <div class="form-hint">Kepala Jurusan yang akan menyetujui permohonan ini</div>
            @error('kepala_jurusan_id')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Barang *</label>
            <select name="item_id" class="form-select" required onchange="updateItemInfo(this.value)">
                <option value="">Pilih barang...</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}"
                            {{ $preselectedItemId && $preselectedItemId == $item->id ? 'selected' : '' }}
                            data-stock="{{ $item->stock }}"
                            data-category="{{ $item->category->name ?? '-' }}"
                            data-location="{{ $item->location->name ?? '-' }}">
                        {{ $item->name }} (Stok: {{ $item->stock }})
                    </option>
                @endforeach
            </select>
            <div id="itemInfo" class="item-info" style="display: none;">
                <div class="item-info-row">
                    <span>Kategori:</span>
                    <span id="itemCategory">-</span>
                </div>
                <div class="item-info-row">
                    <span>Lokasi:</span>
                    <span id="itemLocation">-</span>
                </div>
                <div class="item-info-row">
                    <span>Stok Tersedia:</span>
                    <span id="itemStock">-</span>
                </div>
            </div>
            @error('item_id')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Jumlah *</label>
                <input type="number" name="quantity" class="form-input" min="1" required placeholder="Masukkan jumlah">
                @error('quantity')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Tanggal Pinjam *</label>
                <input type="date" name="borrow_date" class="form-input" required>
                @error('borrow_date')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Tanggal Kembali *</label>
                <input type="date" name="return_date" class="form-input" required>
                @error('return_date')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Jam Kembali *</label>
                <input type="time" name="return_time" class="form-input" required>
                @error('return_time')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Tujuan Peminjaman *</label>
            <textarea name="purpose" class="form-textarea" required placeholder="Jelaskan tujuan peminjaman barang ini..."></textarea>
            @error('purpose')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Catatan Tambahan</label>
            <textarea name="notes" class="form-textarea" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            @error('notes')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-actions">
            <a href="{{ route('teacher.peminjaman-guru') }}" class="cancel-btn">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Batal
            </a>
            <button type="submit" class="submit-btn">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Ajukan Permohonan
            </button>
        </div>
    </form>
</div>

<script>
function updateItemInfo(itemId) {
    const select = document.querySelector('select[name="item_id"]');
    const option = select.querySelector(`option[value="${itemId}"]`);
    const itemInfo = document.getElementById('itemInfo');
    
    if (option && itemId) {
        document.getElementById('itemCategory').textContent = option.dataset.category;
        document.getElementById('itemLocation').textContent = option.dataset.location;
        document.getElementById('itemStock').textContent = option.dataset.stock;
        itemInfo.style.display = 'block';
        
        // Update max quantity
        const quantityInput = document.querySelector('input[name="quantity"]');
        quantityInput.max = option.dataset.stock;
    } else {
        itemInfo.style.display = 'none';
    }
}

// Set minimum dates
const today = new Date().toISOString().split('T')[0];
document.querySelector('input[name="borrow_date"]').min = today;
document.querySelector('input[name="return_date"]').min = today;

// Update return date min when borrow date changes
document.querySelector('input[name="borrow_date"]').addEventListener('change', function() {
    document.querySelector('input[name="return_date"]').min = this.value;
});

// Initialize item info if barang is pre-selected
document.addEventListener('DOMContentLoaded', function() {
    const itemSelect = document.querySelector('select[name="item_id"]');
    if (itemSelect.value) {
        updateItemInfo(itemSelect.value);
    }
});
</script>
@endsection