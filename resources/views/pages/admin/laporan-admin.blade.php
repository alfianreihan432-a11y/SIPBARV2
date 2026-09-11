@extends('layouts.admin')

@section('title', 'Laporan Admin – SIPBAR Admin')
@section('page-heading', 'Laporan Admin')

@section('content')
<div class="panel">
    <div class="panel-title">Laporan Konsolidasi ke Superadmin</div>
    <div class="panel-text">
        Kelola laporan konsolidasi yang dikirim ke Superadmin untuk review dan approval.
    </div>
    
    <div style="margin-top: 20px;">
        <a href="{{ route('admin.laporan-admin.create') }}" class="action-link">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Laporan Baru
        </a>
    </div>
</div>

@if($reports->count() > 0)
    <div class="table-responsive" style="margin-top: 20px;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: var(--table-head-bg);">
                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted);">Judul</th>
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
                            {{ $report->periode_awal->format('d M Y') }} - {{ $report->periode_akhir->format('d M Y') }}
                        </td>
                        <td style="padding: 12px 16px;">
                            <span style="padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; background: rgba({{ $report->status_color === 'success' ? '16, 185, 129' : ($report->status_color === 'danger' ? '239, 68, 68' : '245, 158, 11') }}, 0.15); color: {{ $report->status_color === 'success' ? '#10b981' : ($report->status_color === 'danger' ? '#ef4444' : '#f59e0b') }}; border: 1px solid rgba({{ $report->status_color === 'success' ? '16, 185, 129' : ($report->status_color === 'danger' ? '239, 68, 68' : '245, 158, 11') }}, 0.3);">
                                {{ $report->status_label }}
                            </span>
                        </td>
                        <td style="padding: 12px 16px; color: var(--text-secondary);">
                            {{ $report->created_at->format('d M Y H:i') }}
                        </td>
                        <td style="padding: 12px 16px; text-align: right;">
                            <a href="{{ route('admin.laporan-admin.show', $report->id) }}" style="color: var(--blue); font-size: 12px; font-weight: 600; text-decoration: none;">Detail</a>
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
        <div style="font-size: 14px; font-weight: 600; color: var(--text-subtle);">Belum ada laporan konsolidasi</div>
        <div style="font-size: 12px; margin-top: 4px;">Silakan buat laporan baru untuk dikirim ke Superadmin</div>
    </div>
@endif
@endsection