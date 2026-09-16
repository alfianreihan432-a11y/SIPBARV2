@extends('layouts.superadmin')

@section('title', 'Laporan dari Guru – SIPBAR Superadmin')
@section('page-heading', 'Laporan Masuk dari Guru')

@section('content')
<div class="panel">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div>
            <div class="panel-title">Laporan Masuk dari Guru</div>
            <div class="panel-text">
                Daftar ringkasan laporan statistik peminjaman siswa bimbingan yang dikirim oleh Bapak/Ibu Guru.
            </div>
        </div>
        @if($unreadCount > 0)
            <div style="padding: 6px 14px; background: rgba(251, 191, 36, 0.15); color: var(--color-warning); border: 1px solid rgba(251, 191, 36, 0.3); border-radius: 999px; font-size: 12px; font-weight: 700;">
                {{ $unreadCount }} Laporan Belum Dibaca
            </div>
        @endif
    </div>
</div>

@if(session('success'))
    <div style="margin-top: 16px; padding: 12px 16px; background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: var(--color-success); border-radius: 10px; font-size: 13px; font-weight: 600;">
        {{ session('success') }}
    </div>
@endif

@if($reports->count() > 0)
    <div class="table-responsive" style="margin-top: 20px;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: var(--table-head-bg);">
                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted);">Nama Guru</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted);">Judul Laporan</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted);">Periode</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted);">Status</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted);">Tanggal Kirim</th>
                    <th style="padding: 12px 16px; text-align: right; font-size: 12px; font-weight: 700; color: var(--text-muted);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                    <tr style="border-bottom: 1px solid var(--border-alt); background: {{ $report->isUnread() ? 'var(--bg-card-subtle)' : 'transparent' }};">
                        <td style="padding: 12px 16px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--blue-dark); color: #ffffff !important; display: flex; align-items: center; justify-content: center; font-size: 11.5px; font-weight: 700; flex-shrink: 0;">
                                    {{ strtoupper(substr($report->guru->name ?? 'G', 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: var(--text-primary);">{{ $report->guru->name ?? 'Guru Tidak Ditemukan' }}</div>
                                    <div style="font-size: 11px; color: var(--text-muted);">{{ $report->guru->nip ? 'NIP: ' . $report->guru->nip : ($report->guru->email ?? '-') }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 12px 16px;">
                            <div style="font-weight: 600; color: var(--text-primary);">{{ $report->judul }}</div>
                            @if($report->catatan)
                                <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 3px; font-style: italic;">
                                    "{{ Str::limit($report->catatan, 60) }}"
                                </div>
                            @endif
                        </td>
                        <td style="padding: 12px 16px; color: var(--text-secondary); font-size: 12.5px;">
                            {{ $report->periode }}
                        </td>
                        <td style="padding: 12px 16px;">
                            @if($report->isUnread())
                                <span style="padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; background: rgba(251, 191, 36, 0.15); color: var(--color-warning); border: 1px solid rgba(251, 191, 36, 0.3); display:inline-flex;align-items:center;gap:5px">
                                    <span style="width:7px;height:7px;border-radius:50%;background:currentColor;flex-shrink:0"></span>Belum Dibaca
                                </span>
                            @else
                                <span style="padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; background: rgba(16, 185, 129, 0.15); color: var(--color-success); border: 1px solid rgba(16, 185, 129, 0.3); display:inline-flex;align-items:center;gap:5px">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    Sudah Dibaca
                                </span>
                            @endif
                        </td>
                        <td style="padding: 12px 16px; color: var(--text-muted); font-size: 12px;">
                            {{ $report->created_at->format('d M Y, H:i') }}
                        </td>
                        <td style="padding: 12px 16px; text-align: right;">
                            <a href="{{ route('superadmin.laporan-guru.show', $report->id) }}" style="padding: 6px 12px; background: var(--blue-dark); color: #ffffff !important; border-radius: 6px; font-size: 12px; font-weight: 700; text-decoration: none; display: inline-block;">
                                Lihat Detail
                            </a>
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
    <div style="text-align: center; padding: 48px; background: var(--bg-card); border: 1px solid var(--border-alt); border-radius: 16px; margin-top: 20px;">
        <svg xmlns="http://www.w3.org/2000/svg" style="width: 48px; height: 48px; color: var(--text-subtle); margin-bottom: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
        <div style="font-size: 15px; font-weight: 700; color: var(--text-primary);">Belum ada laporan dari Guru</div>
        <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 4px;">Laporan yang dikirim oleh Guru akan muncul di sini.</div>
    </div>
@endif
@endsection
