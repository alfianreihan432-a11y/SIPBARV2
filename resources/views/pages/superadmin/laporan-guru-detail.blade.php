@extends('layouts.superadmin')

@section('title', 'Detail Laporan Guru – SIPBAR Superadmin')
@section('page-heading', 'Detail Laporan Guru')

@section('content')
@php
    $snap = $report->data_laporan ?? [];
@endphp

{{-- Header Action Panel --}}
<div class="panel">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div>
            <div class="panel-title">{{ $report->judul }}</div>
            <div class="panel-text">{{ $report->periode }}</div>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <a href="{{ route('superadmin.laporan-guru.index') }}" style="padding: 8px 16px; background: var(--bg-card-subtle); color: var(--text-muted); border: 1px solid var(--border-subtle); border-radius: 8px; font-size: 12.5px; font-weight: 700; text-decoration: none;">
                ← Kembali ke Daftar
            </a>
            <form method="POST" action="{{ route('superadmin.laporan-guru.destroy', $report->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" style="padding: 8px 16px; background: rgba(239, 68, 68, 0.15); color: var(--color-danger); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px; font-size: 12.5px; font-weight: 700; cursor: pointer;">
                    Hapus Laporan
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Informasi Pengirim & Status --}}
<div class="panel" style="margin-top: 20px;">
    <div style="font-size: 14px; font-weight: 700; color: var(--text-primary); margin-bottom: 16px;">Informasi Pengirim & Status</div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
        <div>
            <div style="font-size: 11px; font-weight: 700; color: var(--text-subtle); text-transform: uppercase; margin-bottom: 4px;">Guru Pengirim</div>
            <div style="font-size: 14px; font-weight: 700; color: var(--text-primary);">
                {{ $report->guru->name ?? 'Guru Tidak Ditemukan' }}
            </div>
            <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 2px;">
                {{ $report->guru->nip ? 'NIP: ' . $report->guru->nip : ($report->guru->email ?? '-') }}
            </div>
        </div>

        <div>
            <div style="font-size: 11px; font-weight: 700; color: var(--text-subtle); text-transform: uppercase; margin-bottom: 4px;">Tanggal Terkirim</div>
            <div style="font-size: 13.5px; font-weight: 600; color: var(--text-secondary);">
                {{ $report->created_at->format('d M Y, H:i') }} WIB
            </div>
        </div>

        <div>
            <div style="font-size: 11px; font-weight: 700; color: var(--text-subtle); text-transform: uppercase; margin-bottom: 4px;">Status Dibaca</div>
            <div>
                <span style="padding: 4px 10px; border-radius: 6px; font-size: 11.5px; font-weight: 700; background: rgba(16, 185, 129, 0.15); color: var(--color-success); border: 1px solid rgba(16, 185, 129, 0.3); display:inline-flex;align-items:center;gap:5px">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    Sudah Dibaca ({{ $report->read_at ? $report->read_at->format('d M Y, H:i') : 'Baru Saja' }})
                </span>
            </div>
        </div>
    </div>

    @if($report->catatan)
        <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border-alt);">
            <div style="font-size: 11.5px; font-weight: 700; color: var(--text-subtle); text-transform: uppercase; margin-bottom: 6px;">Pesan / Catatan dari Guru:</div>
            <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 10px; padding: 14px; font-size: 13px; color: var(--text-primary); line-height: 1.6; font-style: italic;">
                "{{ $report->catatan }}"
            </div>
        </div>
    @endif
</div>

{{-- Snapshot Data Rekapitulasi Laporan Guru --}}
<div class="panel" style="margin-top: 20px;">
    <div style="font-size: 15px; font-weight: 800; color: var(--text-primary); margin-bottom: 18px;">
        Snapshot Ringkasan Statistik Peminjaman Siswa Bimbingan
    </div>

    {{-- Stat Grid --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 24px;">
        <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 16px;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-subtle); text-transform: uppercase; margin-bottom: 6px;">Total Pengajuan</div>
            <div style="font-size: 26px; font-weight: 800; color: var(--text-primary);">{{ number_format($snap['total_requests'] ?? 0) }}</div>
        </div>

        <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 16px;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-subtle); text-transform: uppercase; margin-bottom: 6px;">Pending</div>
            <div style="font-size: 26px; font-weight: 800; color: var(--color-warning);">{{ number_format($snap['pending_requests'] ?? 0) }}</div>
        </div>

        <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 16px;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-subtle); text-transform: uppercase; margin-bottom: 6px;">Disetujui / Aktif</div>
            <div style="font-size: 26px; font-weight: 800; color: var(--color-info);">{{ number_format($snap['approved_requests'] ?? 0) }}</div>
        </div>

        <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 16px;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-subtle); text-transform: uppercase; margin-bottom: 6px;">Selesai</div>
            <div style="font-size: 26px; font-weight: 800; color: var(--color-success);">{{ number_format($snap['completed_requests'] ?? 0) }}</div>
        </div>

        <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 16px;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-subtle); text-transform: uppercase; margin-bottom: 6px;">Siswa Aktif</div>
            <div style="font-size: 26px; font-weight: 800; color: #a855f7;">{{ number_format($snap['active_students'] ?? 0) }}</div>
        </div>
    </div>

    {{-- Monthly & Rate Details --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
        <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 18px; text-align: center;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-subtle); text-transform: uppercase; margin-bottom: 6px;">Pengajuan Bulan Ini</div>
            <div style="font-size: 32px; font-weight: 800; color: var(--blue);">{{ number_format($snap['this_month_requests'] ?? 0) }}</div>
        </div>

        <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 18px; text-align: center;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-subtle); text-transform: uppercase; margin-bottom: 6px;">Completion Rate</div>
            <div style="font-size: 32px; font-weight: 800; color: var(--color-success);">{{ $snap['completion_rate'] ?? 0 }}%</div>
        </div>

        <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 18px; text-align: center;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-subtle); text-transform: uppercase; margin-bottom: 6px;">Rejection Rate</div>
            <div style="font-size: 32px; font-weight: 800; color: var(--color-danger);">{{ $snap['rejection_rate'] ?? 0 }}%</div>
        </div>
    </div>
</div>

{{-- Daftar Pengajuan Siswa Bimbingan --}}
@if($studentRequests && $studentRequests->count() > 0)
<div class="panel" style="margin-top: 20px;">
    <div style="font-size: 15px; font-weight: 800; color: var(--text-primary); margin-bottom: 18px;">
        Daftar Pengajuan Siswa Bimbingan ({{ $studentRequests->count() }})
    </div>
    <div class="table-responsive">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: var(--bg-card-subtle);">
                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-subtle);">ID</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-subtle);">Siswa</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-subtle);">Barang</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-subtle);">Jumlah</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-subtle);">Status</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-subtle);">Tanggal</th>
                    <th style="padding: 12px 16px; text-align: right; font-size: 12px; font-weight: 700; color: var(--text-subtle);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($studentRequests as $req)
                <tr style="border-bottom: 1px solid var(--border-alt);">
                    <td style="padding: 12px 16px;">#{{ $req->id }}</td>
                    <td style="padding: 12px 16px;">{{ $req->user->name ?? '-' }}</td>
                    <td style="padding: 12px 16px;">{{ $req->itemWithTrashed?->name ?? $req->item?->name ?? '-' }}</td>
                    <td style="padding: 12px 16px;">{{ $req->quantity ?? 1 }}</td>
                    <td style="padding: 12px 16px;">{{ ucfirst($req->status) }}</td>
                    <td style="padding: 12px 16px;">{{ $req->created_at->format('d/m/Y') }}</td>
                    <td style="padding: 12px 16px; text-align: right;">
                        <button type="button" onclick="showStudentDetailModal({{ $req->id }})" style="padding: 6px 12px; background: var(--blue); color: #ffffff; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">
                            Detail
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Detail Modal for Student Requests --}}
<div id="studentDetailModal" class="modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:100;align-items:center;justify-content:center;backdrop-filter:blur(3px);">
    <div style="background:var(--bg-card);border-radius:16px;padding:28px;width:100%;max-width:600px;border:1px solid var(--border-alt);box-shadow:0 20px 48px rgba(0,0,0,0.18);max-height:90vh;overflow-y:auto;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h3 style="font-size:18px;font-weight:700;color:var(--text-primary);">Detail Peminjaman Siswa</h3>
            <button type="button" onclick="hideStudentDetailModal()" style="background:none;border:none;font-size:24px;color:var(--text-muted);cursor:pointer;">&times;</button>
        </div>
        <div id="studentDetailContent"></div>
    </div>
</div>

<script>
const studentRequestsData = @json($studentRequests ?? []);

function showStudentDetailModal(requestId) {
    const req = studentRequestsData.find(r => r.id === requestId);
    if (!req) return;

    let content = '<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">';
    content += '<div>';
    content += '<div style="font-size:11px;font-weight:700;color:var(--text-subtle);text-transform:uppercase;margin-bottom:4px;">Data Peminjam</div>';
    content += '<div style="font-size:14px;font-weight:600;color:var(--text-primary);">' + (req.user?.name || '-') + '</div>';
    content += '<div style="font-size:12px;color:var(--text-muted);margin-top:2px;">' + (req.user?.jurusan?.nama || '-') + (req.user?.kelas ? ' (' + req.user.kelas + ')' : '') + '</div>';
    content += '<div style="font-size:12px;color:var(--text-muted);margin-top:2px;">WA: ' + (req.user?.whatsapp_number || '-') + '</div>';
    content += '</div>';
    content += '<div>';
    content += '<div style="font-size:11px;font-weight:700;color:var(--text-subtle);text-transform:uppercase;margin-bottom:4px;">Guru Penanggung Jawab</div>';
    content += '<div style="font-size:14px;font-weight:600;color:var(--text-primary);">' + (req.teacher?.name || '-') + '</div>';
    content += '</div>';
    content += '</div>';

    content += '<div style="margin-bottom:20px;">';
    content += '<div style="font-size:11px;font-weight:700;color:var(--text-subtle);text-transform:uppercase;margin-bottom:8px;">Data Pengisian Form</div>';
    content += '<div style="background:var(--bg-card-subtle);border:1px solid var(--border-subtle);border-radius:10px;padding:14px;">';
    content += '<div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border-subtle);">';
    content += '<span style="font-size:12px;color:var(--text-muted);">Keperluan</span>';
    content += '<span style="font-size:12px;font-weight:600;color:var(--text-primary);">' + (req.purpose || '-') + '</span>';
    content += '</div>';
    content += '<div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border-subtle);">';
    content += '<span style="font-size:12px;color:var(--text-muted);">Tanggal Pinjam</span>';
    content += '<span style="font-size:12px;font-weight:600;color:var(--text-primary);">' + (req.borrow_date || '-') + '</span>';
    content += '</div>';
    content += '<div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border-subtle);">';
    content += '<span style="font-size:12px;color:var(--text-muted);">Tanggal Kembali</span>';
    content += '<span style="font-size:12px;font-weight:600;color:var(--text-primary);">' + (req.return_date || '-') + '</span>';
    content += '</div>';
    content += '<div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border-subtle);">';
    content += '<span style="font-size:12px;color:var(--text-muted);">Jam Kembali</span>';
    content += '<span style="font-size:12px;font-weight:600;color:var(--text-primary);">' + (req.return_time || '-') + '</span>';
    content += '</div>';
    if (req.notes) {
        content += '<div style="display:flex;justify-content:space-between;padding:6px 0;">';
        content += '<span style="font-size:12px;color:var(--text-muted);">Catatan</span>';
        content += '<span style="font-size:12px;font-weight:600;color:var(--text-primary);">' + (req.notes || '-') + '</span>';
        content += '</div>';
    }
    content += '</div>';
    content += '</div>';

    content += '<div>';
    content += '<div style="font-size:11px;font-weight:700;color:var(--text-subtle);text-transform:uppercase;margin-bottom:8px;">Data Barang Dipinjam</div>';
    content += '<div style="background:var(--bg-card-subtle);border:1px solid var(--border-subtle);border-radius:10px;padding:14px;">';
    content += '<div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border-subtle);">';
    content += '<span style="font-size:12px;color:var(--text-muted);">Nama Barang</span>';
    content += '<span style="font-size:12px;font-weight:600;color:var(--text-primary);">' + (req.itemWithTrashed?.name || req.item?.name || '-') + '</span>';
    content += '</div>';
    content += '<div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border-subtle);">';
    content += '<span style="font-size:12px;color:var(--text-muted);">Kode Barang</span>';
    content += '<span style="font-size:12px;font-weight:600;color:var(--text-primary);">' + (req.itemWithTrashed?.code || req.item?.code || '-') + '</span>';
    content += '</div>';
    content += '<div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border-subtle);">';
    content += '<span style="font-size:12px;color:var(--text-muted);">Jumlah</span>';
    content += '<span style="font-size:12px;font-weight:600;color:var(--text-primary);">' + (req.quantity || 1) + ' unit</span>';
    content += '</div>';
    content += '<div style="display:flex;justify-content:space-between;padding:6px 0;">';
    content += '<span style="font-size:12px;color:var(--text-muted);">Kategori</span>';
    content += '<span style="font-size:12px;font-weight:600;color:var(--text-primary);">' + (req.itemWithTrashed?.category?.name || req.item?.category?.name || '-') + '</span>';
    content += '</div>';
    content += '</div>';
    content += '</div>';

    document.getElementById('studentDetailContent').innerHTML = content;
    document.getElementById('studentDetailModal').style.display = 'flex';
}

function hideStudentDetailModal() {
    document.getElementById('studentDetailModal').style.display = 'none';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideStudentDetailModal();
    }
});
</script>
@endsection
