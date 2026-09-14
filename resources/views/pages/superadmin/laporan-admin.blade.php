@extends('layouts.superadmin')

@section('title', 'Laporan Admin – SIPBAR Superadmin')
@section('page-heading', 'Laporan dari Admin')

@section('content')
<div class="panel">
    <div class="panel-title">Laporan Konsolidasi dari Admin</div>
    <div class="panel-text">
        Review dan approve/reject laporan konsolidasi yang dikirim oleh Admin.
    </div>
</div>

@if($reports->count() > 0)
    <div class="table-responsive" style="margin-top: 20px;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: var(--table-head-bg);">
                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted);">Judul</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted);">Pengirim</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted);">Periode</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted);">Status</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted);">Tanggal Kirim</th>
                    <th style="padding: 12px 16px; text-align: right; font-size: 12px; font-weight: 700; color: var(--text-muted);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                    <tr style="border-bottom: 1px solid var(--border-alt);">
                        <td style="padding: 12px 16px;">
                            <div style="font-weight: 600; color: var(--text-primary);">{{ $report->judul }}</div>
                            @if($report->deskripsi)
                                <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">{{ Str::limit($report->deskripsi, 100) }}</div>
                            @endif
                        </td>
                        <td style="padding: 12px 16px; color: var(--text-secondary);">
                            {{ $report->pengirim->name ?? 'Unknown' }}
                        </td>
                        <td style="padding: 12px 16px; color: var(--text-secondary);">
                            {{ $report->periode_awal->format('d M Y') }} - {{ $report->periode_akhir->format('d M Y') }}
                        </td>
                        <td style="padding: 12px 16px;">
                            <span style="padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; background: rgba({{ $report->status_color === 'success' ? '16, 185, 129' : ($report->status_color === 'danger' ? '239, 68, 68' : '251, 191, 36') }}, 0.15); color: {{ $report->status_color === 'success' ? 'var(--color-success)' : ($report->status_color === 'danger' ? 'var(--color-danger)' : 'var(--color-warning)') }}; border: 1px solid rgba({{ $report->status_color === 'success' ? '16, 185, 129' : ($report->status_color === 'danger' ? '239, 68, 68' : '251, 191, 36') }}, 0.3);">
                                {{ $report->status_label }}
                            </span>
                        </td>
                        <td style="padding: 12px 16px; color: var(--text-secondary);">
                            {{ $report->created_at->format('d M Y H:i') }}
                        </td>
                        <td style="padding: 12px 16px; text-align: right;">
                            <a href="{{ route('superadmin.laporan-admin.show', $report->id) }}" style="color: var(--blue); font-size: 12px; font-weight: 600; text-decoration: none;">Review</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 20px; display: flex; justify-content: center;">
        {{ $reports->links() }}
    </div>
@else
    <div style="text-align: center; padding: 40px; color: var(--text-muted);">
        <div style="font-size: 14px; font-weight: 600; color: var(--text-subtle);">Belum ada laporan dari Admin</div>
        <div style="font-size: 12px; margin-top: 4px;">Tunggu Admin mengirim laporan konsolidasi</div>
    </div>
@endif
@endsection