@extends('layouts.admin')

@section('title', 'Detail Laporan Admin – SIPBAR Admin')
@section('page-heading', 'Detail Laporan Konsolidasi')

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
        <a href="{{ route('admin.laporan-admin') }}" style="padding: 8px 16px; background: var(--bg-card-subtle); color: var(--text-muted); border: 1px solid var(--border-subtle); border-radius: 8px; font-size: 12px; font-weight: 700; text-decoration: none;">
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
                <span style="padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; background: rgba({{ $report->status_color === 'success' ? '16, 185, 129' : ($report->status_color === 'danger' ? '239, 68, 68' : '245, 158, 11') }}, 0.15); color: {{ $report->status_color === 'success' ? '#10b981' : ($report->status_color === 'danger' ? '#ef4444' : '#f59e0b') }}; border: 1px solid rgba({{ $report->status_color === 'success' ? '16, 185, 129' : ($report->status_color === 'danger' ? '239, 68, 68' : '245, 158, 11') }}, 0.3);">
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
                <div style="font-size: 24px; font-weight: 800; color: #10b981;">{{ $report->data_rekap['total_dikembalikan'] ?? 0 }}</div>
            </div>
            <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 10px; padding: 16px;">
                <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Pending</div>
                <div style="font-size: 24px; font-weight: 800; color: #f59e0b;">{{ $report->data_rekap['total_pending'] ?? 0 }}</div>
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

@if($report->catatan_superadmin)
    <div class="panel" style="margin-top: 20px;">
        <div style="font-size: 14px; font-weight: 700; color: var(--text-primary); margin-bottom: 12px;">Catatan Superadmin</div>
        <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 10px; padding: 16px; font-size: 13px; color: var(--text-secondary);">
            {{ $report->catatan_superadmin }}
        </div>
    </div>
@endif
@endsection