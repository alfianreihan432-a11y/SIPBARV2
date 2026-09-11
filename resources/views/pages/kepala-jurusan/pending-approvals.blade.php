@extends('layouts.kepala-jurusan')

@section('title', 'Permohonan Peminjaman Guru')
@section('page-heading', 'Permohonan Peminjaman Guru')

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
    .status-pending { background: var(--accent-light); color: var(--accent); }
    
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
    .btn-approve {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }
    html.dark .btn-approve { color: #34d399; }
    .btn-approve:hover {
        background: rgba(16, 185, 129, 0.18);
    }
    .btn-reject {
        background: var(--accent-light);
        color: var(--accent);
    }
    .btn-reject:hover {
        background: rgba(239, 68, 68, 0.18);
        color: var(--accent-hover);
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
    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
    }
</style>

<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">Permohonan Peminjaman Guru</h2>
        <span style="font-size: 13px; color: var(--muted);">
            {{ $pendingRequests->total() }} permohonan
        </span>
    </div>
    
    @if($pendingRequests->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Guru</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Tujuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingRequests as $request)
                    <tr>
                        <td>{{ $request->user->name }}</td>
                        <td>{{ $request->item->name }}</td>
                        <td>{{ $request->quantity }}</td>
                        <td>{{ $request->borrow_date->format('d/m/Y') }}</td>
                        <td>{{ $request->return_date->format('d/m/Y') }}</td>
                        <td>{{ Str::limit($request->purpose, 30) }}</td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <form method="POST" action="{{ route('kajur.approve-request', $request->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="action-btn btn-approve" onclick="return confirm('Setujui permohonan peminjaman ini?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Setujui
                                    </button>
                                </form>
                                <button type="button" class="action-btn btn-reject" onclick="showRejectModal({{ $request->id }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Tolak
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($pendingRequests->hasPages())
            <div style="margin-top: 20px; display: flex; justify-content: center; gap: 8px;">
                {{ $pendingRequests->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p>Tidak ada permohonan peminjaman yang menunggu persetujuan</p>
        </div>
    @endif
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <h3 class="modal-title">Tolak Permohonan Peminjaman</h3>
        <form method="POST" action="" id="rejectForm">
            @csrf
            <input type="hidden" name="id" id="rejectId">
            
            <div class="form-group">
                <label class="form-label">Alasan Penolakan *</label>
                <textarea 
                    name="rejection_reason" 
                    class="form-input" 
                    rows="4" 
                    required
                    placeholder="Jelaskan alasan penolakan permohonan ini..."
                ></textarea>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="action-btn" style="background: var(--bg3); color: var(--text);" onclick="hideRejectModal()">
                    Batal
                </button>
                <button type="submit" class="action-btn btn-reject">
                    Tolak Permohonan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal(id) {
    document.getElementById('rejectId').value = id;
    document.getElementById('rejectForm').action = '{{ route('kajur.reject-request', ':id') }}'.replace(':id', id);
    document.getElementById('rejectModal').classList.add('show');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.remove('show');
    document.getElementById('rejectForm').reset();
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideRejectModal();
    }
});
</script>
@endsection