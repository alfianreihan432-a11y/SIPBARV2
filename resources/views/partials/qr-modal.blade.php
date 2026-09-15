{{-- QR Code Modal Component (AJAX) --}}
<div id="qr-modal-overlay" class="qr-modal-overlay" tabindex="-1" aria-hidden="true">
    <div id="qr-modal-card" class="qr-modal-card">
        <button type="button" onclick="closeQRModal()" class="qr-modal-close-btn" aria-label="Tutup">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="qr-modal-header">
            <div class="qr-modal-title">QR Code Peminjaman</div>
            <div class="qr-modal-subtitle">Tunjukkan QR Code ini kepada petugas inventaris saat mengambil barang</div>
        </div>

        {{-- Spinner Loader --}}
        <div id="qr-spinner" class="qr-spinner-wrap">
            <div class="qr-spinner"></div>
            <div style="margin-top:12px;font-size:12px;color:var(--muted)">Memuat QR Code...</div>
        </div>

        {{-- Error Alert --}}
        <div id="qr-error" class="qr-error-box" style="display:none"></div>

        {{-- Modal Content (Loaded via AJAX) --}}
        <div id="qr-body-content" style="display:none">
            <div class="qr-img-wrapper">
                <img id="qr-img-el" src="" alt="QR Code Peminjaman">
            </div>

            <table class="qr-details-table">
                <tr>
                    <td class="qr-details-label">ID Peminjaman</td>
                    <td id="qr-detail-id" class="qr-details-val">#--</td>
                </tr>
                <tr>
                    <td class="qr-details-label">Nama Barang</td>
                    <td id="qr-detail-item" class="qr-details-val">-</td>
                </tr>
                <tr>
                    <td class="qr-details-label">Status</td>
                    <td class="qr-details-val">
                        <span id="qr-detail-status" class="qr-status-badge">
                            ● -
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="qr-details-label">Masa Berlaku</td>
                    <td id="qr-detail-expires" class="qr-details-val">-</td>
                </tr>
            </table>

            <div class="qr-notice-box">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="flex-shrink:0">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    Pastikan layar ponsel cukup terang agar QR Code dapat terbaca oleh scanner petugas.
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.qr-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.65);
    backdrop-filter: blur(4px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    pointer-events: none;
    transition: opacity .25s ease;
}
.qr-modal-overlay.active {
    opacity: 1;
    pointer-events: auto;
}
.qr-modal-card {
    background: var(--card, #ffffff);
    border: 1px solid var(--border2, #e2e8f0);
    border-radius: 20px;
    padding: 28px 24px 24px;
    max-width: 420px;
    width: 100%;
    position: relative;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
    transform: scale(.92) translateY(16px);
    transition: transform .28s cubic-bezier(.34, 1.56, .64, 1), opacity .25s ease;
    opacity: 0;
}
.qr-modal-overlay.active .qr-modal-card {
    transform: scale(1) translateY(0);
    opacity: 1;
}
.qr-modal-close-btn {
    position: absolute;
    top: 16px;
    right: 16px;
    background: var(--bg3, #f1f5f9);
    border: 1px solid var(--border2, #e2e8f0);
    border-radius: 10px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: var(--muted, #64748b);
    transition: all .15s ease;
}
.qr-modal-close-btn:hover {
    background: var(--border2, #cbd5e1);
    color: var(--text, #0f172a);
}
.qr-modal-header {
    text-align: center;
    margin-bottom: 20px;
}
.qr-modal-title {
    font-family: var(--font-head, 'Space Grotesk', sans-serif);
    font-size: 18px;
    font-weight: 800;
    color: var(--text, #0d1829);
    letter-spacing: -.01em;
}
.qr-modal-subtitle {
    font-size: 12px;
    color: var(--muted, #5a6a7e);
    margin-top: 4px;
    line-height: 1.4;
}
.qr-spinner-wrap {
    text-align: center;
    padding: 40px 0;
}
.qr-spinner {
    width: 42px;
    height: 42px;
    border: 3px solid var(--border2, #e2e8f0);
    border-top-color: var(--primary, #2563eb);
    border-radius: 50%;
    animation: qr-modal-spin .7s linear infinite;
    margin: 0 auto;
}
@keyframes qr-modal-spin {
    to { transform: rotate(360deg); }
}
.qr-error-box {
    color: var(--s-rejected, #dc2626);
    font-size: 13px;
    padding: 14px 16px;
    background: var(--s-rejected-bg, #fef2f2);
    border: 1px solid var(--s-rejected-bdr, #fecaca);
    border-radius: 12px;
    text-align: center;
    margin-bottom: 16px;
}
.qr-img-wrapper {
    width: 240px;
    height: 240px;
    margin: 0 auto 20px;
    background: #ffffff;
    border: 2px dashed var(--border2, #cbd5e1);
    border-radius: 16px;
    padding: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.qr-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}
.qr-details-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 18px;
    text-align: left;
    background: var(--bg3, #f8fafc);
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--border2, #e2e8f0);
}
.qr-details-table tr {
    border-bottom: 1px solid var(--border2, #e2e8f0);
}
.qr-details-table tr:last-child {
    border-bottom: none;
}
.qr-details-table td {
    padding: 10px 14px;
    font-size: 12.5px;
}
.qr-details-label {
    color: var(--muted, #64748b);
    font-weight: 500;
    width: 38%;
}
.qr-details-val {
    color: var(--text, #0d1829);
    font-weight: 700;
}
.qr-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 9px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}
.qr-status-badge.approved, .qr-status-badge.qr_ready {
    background: var(--s-approved-bg, rgba(37,99,235,.12));
    color: var(--s-approved, #2563eb);
    border: 1px solid var(--s-approved-bdr, rgba(37,99,235,.25));
}
.qr-status-badge.borrowed {
    background: var(--s-borrowed-bg, rgba(8,145,178,.12));
    color: var(--s-borrowed, #0891b2);
    border: 1px solid var(--s-borrowed-bdr, rgba(8,145,178,.25));
}
.qr-notice-box {
    background: var(--s-pending-bg, rgba(245, 158, 11, 0.1));
    border: 1px solid var(--s-pending-bdr, rgba(245, 158, 11, 0.25));
    color: var(--s-pending, #b45309);
    border-radius: 12px;
    padding: 12px 14px;
    font-size: 12px;
    line-height: 1.5;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    text-align: left;
}
</style>

<script>
(function() {
    window.openQRModal = function(borrowingId) {
        var overlay = document.getElementById('qr-modal-overlay');
        var spinner = document.getElementById('qr-spinner');
        var errorBox = document.getElementById('qr-error');
        var bodyContent = document.getElementById('qr-body-content');

        if (!overlay) return;

        // Reset state
        spinner.style.display = 'block';
        errorBox.style.display = 'none';
        bodyContent.style.display = 'none';

        // Show modal overlay
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';

        // AJAX Fetch data QR Code
        fetch('/siswa/peminjaman/' + borrowingId + '/qrcode/data', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function(res) {
            return res.json();
        })
        .then(function(data) {
            spinner.style.display = 'none';

            if (data.success) {
                // Populate elements
                document.getElementById('qr-img-el').src = data.qr_image;
                document.getElementById('qr-detail-id').textContent = '#' + data.borrowing_id;
                document.getElementById('qr-detail-item').textContent = data.item_name;
                
                var statusBadge = document.getElementById('qr-detail-status');
                statusBadge.className = 'qr-status-badge ' + (data.status || '');
                statusBadge.textContent = '● ' + (data.status_label || data.status || '-');

                document.getElementById('qr-detail-expires').textContent = data.expires_at || 'Tidak ada batas waktu';

                bodyContent.style.display = 'block';
            } else {
                errorBox.style.display = 'block';
                errorBox.textContent = data.message || 'Gagal memuat QR Code.';
            }
        })
        .catch(function(err) {
            spinner.style.display = 'none';
            errorBox.style.display = 'block';
            errorBox.textContent = 'Terjadi kesalahan koneksi. Silakan coba beberapa saat lagi.';
        });
    };

    window.closeQRModal = function() {
        var overlay = document.getElementById('qr-modal-overlay');
        if (!overlay) return;
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    };

    // Close on overlay backdrop click
    document.addEventListener('DOMContentLoaded', function() {
        var overlay = document.getElementById('qr-modal-overlay');
        if (overlay) {
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    closeQRModal();
                }
            });
        }
    });

    // Close on ESC key press
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeQRModal();
        }
    });
})();
</script>
