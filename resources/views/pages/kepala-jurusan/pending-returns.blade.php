@extends('layouts.kepala-jurusan')

@section('title', 'Pengembalian Guru')
@section('page-heading', 'Pengembalian Guru')

@section('content')
<style>
    .section-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 22px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(133, 30, 42, 0.05), 0 1px 3px rgba(0,0,0,0.03);
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }
    .section-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-title::before {
        content: '';
        width: 3.5px;
        height: 16px;
        background: var(--accent);
        border-radius: 2px;
        display: inline-block;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    .table th {
        text-align: left;
        padding: 12px;
        font-size: 12px;
        font-weight: 600;
        color: var(--muted);
        border-bottom: 1px solid var(--border);
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .table td {
        padding: 12px;
        font-size: 13px;
        color: var(--text);
        border-bottom: 1px solid var(--border);
    }
    .table tr:last-child td {
        border-bottom: none;
    }
    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .status-returned { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    
    .condition-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
    }
    .condition-baik { background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.25); }
    html.dark .condition-baik { color: #059669; }
    .condition-rusak-ringan { background: rgba(245, 158, 11, 0.12); color: #b45309; border: 1px solid rgba(245, 158, 11, 0.25); }
    html.dark .condition-rusak-ringan { color: #b45309; }
    .condition-rusak-berat { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-verify {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }
    html.dark .btn-verify { color: #34d399; }
    .btn-verify:hover {
        background: rgba(16, 185, 129, 0.18);
    }
    .btn-verify-modal {
        background: #059669;
        color: #ffffff;
    }
    .btn-verify-modal:hover {
        background: #047857;
    }
    
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--muted);
    }
    .empty-state svg {
        width: 48px;
        height: 48px;
        margin-bottom: 12px;
        opacity: 0.5;
    }
    
    .modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 100;
        align-items: center;
        justify-content: center;
    }
    .modal.show {
        display: flex;
    }
    .modal-content {
        background: var(--card);
        border-radius: 12px;
        padding: 24px;
        width: 100%;
        max-width: 480px;
        border: 1px solid var(--border);
    }
    .modal-title {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 16px;
        color: var(--text);
    }
    .form-group {
        margin-bottom: 16px;
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
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 13px;
        background: var(--input-bg);
        color: var(--text);
        outline: none;
    }
    .form-input:focus {
        border-color: var(--accent);
    }
    .form-select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 13px;
        background: var(--input-bg) !important;
        color: var(--text) !important;
        outline: none;
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
    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
    }
</style>

<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">Pengembalian Guru Menunggu Verifikasi</h2>
        <span style="font-size: 13px; color: var(--muted);">
            {{ $pendingReturns->total() }} pengembalian
        </span>
    </div>
    
    @if($pendingReturns->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Guru</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Tanggal Kembali</th>
                    <th>Kondisi Barang</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingReturns as $return)
                    @php
                        $borrowing = $return->borrowingRequest;
                        $item = $borrowing->item ?? $borrowing->itemWithTrashed;
                    @endphp
                    <tr>
                        <td>{{ $return->user->name }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $borrowing->quantity }}</td>
                        <td>{{ $return->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($return->kondisi_barang === 'baik')
                                <span class="condition-badge condition-baik">Baik</span>
                            @elseif($return->kondisi_barang === 'rusak_ringan')
                                <span class="condition-badge condition-rusak-ringan">Rusak Ringan</span>
                            @elseif($return->kondisi_barang === 'rusak_berat')
                                <span class="condition-badge condition-rusak-berat">Rusak Berat</span>
                            @elseif($return->kondisi_barang === 'hilang')
                                <span class="condition-badge" style="background: rgba(100, 116, 139, 0.15); color: #64748b; border: 1px solid rgba(100, 116, 139, 0.3);">Hilang</span>
                            @else
                                <span class="condition-badge">{{ $return->kondisi_label }}</span>
                            @endif

                            @if($return->foto_bukti)
                                <div style="margin-top: 4px;">
                                    <a href="{{ asset('storage/' . $return->foto_bukti) }}" target="_blank" style="font-size: 11px; color: var(--accent); display: inline-flex; align-items: center; gap: 4px; text-decoration: none; font-weight: 600;">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Foto Bukti
                                    </a>
                                </div>
                            @endif
                        </td>
                        <td>{{ Str::limit($return->catatan ?? '-', 20) }}</td>
                        <td>
                            <button type="button" class="action-btn btn-verify" onclick="showVerifyModal({{ $return->id }}, '{{ $return->kondisi_label }}', '{{ $return->catatan ?? '' }}', '{{ $return->foto_bukti ? asset('storage/' . $return->foto_bukti) : '' }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Verifikasi
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($pendingReturns->hasPages())
            <div style="margin-top: 20px; display: flex; justify-content: center; gap: 8px;">
                {{ $pendingReturns->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p>Tidak ada pengembalian yang menunggu verifikasi</p>
        </div>
    @endif
</div>

<!-- Verify Modal -->
<div id="verifyModal" class="modal">
    <div class="modal-content">
        <h3 class="modal-title">Verifikasi Pengembalian Barang</h3>
        <form method="POST" action="" id="verifyForm">
            @csrf
            <input type="hidden" name="id" id="verifyId">
            
            <div class="form-group">
                <label class="form-label">Kondisi Barang (Verifikasi) *</label>
                <select name="verified_condition" class="form-select" required>
                    <option value="">Pilih kondisi barang...</option>
                    <option value="Baik">Baik</option>
                    <option value="Rusak Ringan">Rusak Ringan</option>
                    <option value="Rusak Berat">Rusak Berat</option>
                    <option value="Hilang">Hilang</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Catatan Verifikasi</label>
                <textarea 
                    name="verified_notes" 
                    class="form-input" 
                    rows="3" 
                    placeholder="Tambahkan catatan jika diperlukan..."
                ></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Kondisi Barang (Laporan Guru)</label>
                <div style="padding: 8px 12px; background: var(--bg3); border-radius: 6px; font-size: 13px; color: var(--muted);">
                    <span id="originalCondition">-</span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan Guru</label>
                <div style="padding: 8px 12px; background: var(--bg3); border-radius: 6px; font-size: 13px; color: var(--muted);">
                    <span id="originalNotes">-</span>
                </div>
            </div>

            <div class="form-group" id="photoEvidenceSection" style="display: none;">
                <label class="form-label">Foto Bukti</label>
                <div style="padding: 8px;">
                    <a id="photoEvidenceLink" href="#" target="_blank" style="display: inline-block; padding: 8px 12px; background: var(--bg3); border-radius: 6px; font-size: 12px; color: var(--accent); text-decoration: none; font-weight: 600;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;vertical-align:middle;margin-right:4px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Lihat Foto Bukti
                    </a>
                </div>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="action-btn" style="background: var(--bg3); color: var(--text);" onclick="hideVerifyModal()">
                    Batal
                </button>
                <button type="submit" class="action-btn btn-verify-modal">
                    Verifikasi & Setujui
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showVerifyModal(id, condition, notes, photoUrl) {
    document.getElementById('verifyId').value = id;
    document.getElementById('verifyForm').action = '{{ route('kajur.verify-return', ':id') }}'.replace(':id', id);
    document.getElementById('originalCondition').textContent = condition || '-';
    document.getElementById('originalNotes').textContent = notes || '-';

    // Show/hide photo evidence section
    const photoSection = document.getElementById('photoEvidenceSection');
    if (photoUrl) {
        photoSection.style.display = 'block';
        document.getElementById('photoEvidenceLink').href = photoUrl;
    } else {
        photoSection.style.display = 'none';
    }

    document.getElementById('verifyModal').classList.add('show');
}

function hideVerifyModal() {
    document.getElementById('verifyModal').classList.remove('show');
    document.getElementById('verifyForm').reset();
}

// Close modal when clicking outside
document.getElementById('verifyModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideVerifyModal();
    }
});
</script>
@endsection