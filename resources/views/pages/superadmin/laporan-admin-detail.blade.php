@extends('layouts.superadmin')

@section('title', 'Review Laporan Admin – SIPBAR Superadmin')
@section('page-heading', 'Review Laporan Konsolidasi')

@section('content')
<div class="panel">
    <div class="panel-title">{{ $report->judul }}</div>
    <div class="panel-text">
        @if($report->deskripsi)
            {{ $report->deskripsi }}
        @else
            Tidak ada deskripsi.
        @endif
    </div>
    
    <div style="margin-top: 20px; display: flex; gap: 12px;">
        <a href="{{ route('superadmin.laporan-admin') }}" style="padding: 8px 16px; background: var(--bg-card-subtle); color: var(--text-muted); border: 1px solid var(--border-subtle); border-radius: 8px; font-size: 12px; font-weight: 700; text-decoration: none;">
            Kembali
        </a>
    </div>
</div>

<div class="panel" style="margin-top: 20px;">
    <div style="font-size: 14px; font-weight: 700; color: var(--text-primary); margin-bottom: 16px;">Informasi Laporan</div>
    
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
        <div>
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px;">Periode</div>
            <div style="font-size: 13px; color: var(--text-secondary);">
                {{ $report->periode_awal->format('d M Y') }} - {{ $report->periode_akhir->format('d M Y') }}
            </div>
        </div>
        <div>
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px;">Status</div>
            <div>
                <span style="padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; background: rgba({{ $report->status_color === 'success' ? '16, 185, 129' : ($report->status_color === 'danger' ? '239, 68, 68' : '251, 191, 36') }}, 0.15); color: {{ $report->status_color === 'success' ? 'var(--color-success)' : ($report->status_color === 'danger' ? 'var(--color-danger)' : 'var(--color-warning)') }}; border: 1px solid rgba({{ $report->status_color === 'success' ? '16, 185, 129' : ($report->status_color === 'danger' ? '239, 68, 68' : '251, 191, 36') }}, 0.3);">
                    {{ $report->status_label }}
                </span>
            </div>
        </div>
        <div>
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px;">Dikirim Oleh</div>
            <div style="font-size: 13px; color: var(--text-secondary);">
                {{ $report->pengirim->name ?? 'Unknown' }}
            </div>
        </div>
        <div>
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px;">Tanggal Kirim</div>
            <div style="font-size: 13px; color: var(--text-secondary);">
                {{ $report->created_at->format('d M Y H:i') }}
            </div>
        </div>
    </div>
</div>

@if($report->data_rekap)
    <div class="panel" style="margin-top: 20px;">
        <div style="font-size: 14px; font-weight: 700; color: var(--text-primary); margin-bottom: 16px;">Rekapitulasi Data</div>
        
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
            <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 10px; padding: 16px;">
                <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Total Transaksi</div>
                <div style="font-size: 24px; font-weight: 800; color: var(--text-primary);">{{ $report->data_rekap['total_transaksi'] ?? 0 }}</div>
            </div>
            <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 10px; padding: 16px;">
                <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Dipinjam</div>
                <div style="font-size: 24px; font-weight: 800; color: var(--blue);">{{ $report->data_rekap['total_dipinjam'] ?? 0 }}</div>
            </div>
            <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 10px; padding: 16px;">
                <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Dikembalikan</div>
                <div style="font-size: 24px; font-weight: 800; color: var(--color-success);">{{ $report->data_rekap['total_dikembalikan'] ?? 0 }}</div>
            </div>
            <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 10px; padding: 16px;">
                <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Pending</div>
                <div style="font-size: 24px; font-weight: 800; color: var(--color-warning);">{{ $report->data_rekap['total_pending'] ?? 0 }}</div>
            </div>
        </div>
        
        @if(isset($report->data_rekap['jurusan_reports']) && count($report->data_rekap['jurusan_reports']) > 0)
            <div style="margin-top: 24px;">
                <div style="font-size: 12px; font-weight: 700; color: var(--text-primary); margin-bottom: 12px;">Laporan Jurusan yang Disertakan:</div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    @foreach($report->data_rekap['jurusan_reports'] as $jurusanReport)
                        <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 8px; padding: 12px;">
                            <div style="font-weight: 600; color: var(--text-primary);">{{ $jurusanReport['jurusan'] }}</div>
                            <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">
                                Pengirim: {{ $jurusanReport['pengirim'] }} | Periode: {{ $jurusanReport['periode']['awal'] }} - {{ $jurusanReport['periode']['akhir'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endif

@if($report->status === LaporanAdmin::STATUS_PENDING_REVIEW)
    <div class="panel" style="margin-top: 20px;">
        <div style="font-size: 14px; font-weight: 700; color: var(--text-primary); margin-bottom: 16px;">Review Laporan</div>
        
        <form method="POST" action="{{ route('superadmin.laporan-admin.approve', $report->id) }}">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">Catatan (Opsional)</label>
                <textarea name="catatan_superadmin" rows="3" placeholder="Tambahkan catatan untuk Admin..." style="width: 100%; padding: 10px 12px; border: 1.5px solid var(--border-alt); border-radius: 10px; background: var(--input-bg); color: var(--text-primary); font-size: 13px; outline: none; resize: vertical;"></textarea>
            </div>
            
            <div style="display: flex; gap: 12px;">
                <button type="submit" style="padding: 10px 20px; background: var(--color-success); color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.15s;">
                    Setujui Laporan
                </button>
                <button type="button" onclick="document.getElementById('rejectForm').style.display='block'" style="padding: 10px 20px; background: transparent; color: var(--color-danger); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.15s;">
                    Tolak Laporan
                </button>
            </div>
        </form>
        
        <form id="rejectForm" method="POST" action="{{ route('superadmin.laporan-admin.reject', $report->id) }}" style="display: none; margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border-alt);">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">Alasan Penolakan *</label>
                <textarea name="catatan_superadmin" rows="3" required placeholder="Jelaskan alasan penolakan..." style="width: 100%; padding: 10px 12px; border: 1.5px solid var(--border-alt); border-radius: 10px; background: var(--input-bg); color: var(--text-primary); font-size: 13px; outline: none; resize: vertical;"></textarea>
            </div>
            
            <div style="display: flex; gap: 12px;">
                <button type="submit" style="padding: 10px 20px; background: var(--color-danger); color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.15s;">
                    Konfirmasi Penolakan
                </button>
                <button type="button" onclick="document.getElementById('rejectForm').style.display='none'" style="padding: 10px 20px; background: var(--bg-card-subtle); color: var(--text-muted); border: 1px solid var(--border-subtle); border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.15s;">
                    Batal
                </button>
            </div>
        </form>
    </div>
@endif

@if($report->catatan_superadmin)
    <div class="panel" style="margin-top: 20px;">
        <div style="font-size: 14px; font-weight: 700; color: var(--text-primary); margin-bottom: 12px;">Catatan Superadmin</div>
        <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 10px; padding: 16px; font-size: 13px; color: var(--text-secondary);">
            {{ $report->catatan_superadmin }}
        </div>
    </div>
@endif
@endsection