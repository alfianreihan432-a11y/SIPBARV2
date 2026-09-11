@extends('layouts.admin')

@section('title', 'Buat Laporan Admin – SIPBAR Admin')
@section('page-heading', 'Buat Laporan Konsolidasi')

@section('content')
<div class="panel">
    <div class="panel-title">Buat Laporan Konsolidasi ke Superadmin</div>
    <div class="panel-text">
        Buat laporan konsolidasi dari laporan jurusan yang sudah disetujui untuk dikirim ke Superadmin.
    </div>
</div>

<div class="panel" style="margin-top: 20px;">
    <form method="POST" action="{{ route('admin.laporan-admin.store') }}">
        @csrf
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">Judul Laporan *</label>
            <input type="text" name="judul" required placeholder="Contoh: Laporan Konsolidasi Bulan September 2026" style="width: 100%; padding: 10px 12px; border: 1.5px solid var(--border-alt); border-radius: 10px; background: var(--input-bg); color: var(--text-primary); font-size: 13px; outline: none;">
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">Deskripsi</label>
            <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat tentang laporan ini..." style="width: 100%; padding: 10px 12px; border: 1.5px solid var(--border-alt); border-radius: 10px; background: var(--input-bg); color: var(--text-primary); font-size: 13px; outline: none; resize: vertical;"></textarea>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">Periode Awal *</label>
                <input type="date" name="periode_awal" required style="width: 100%; padding: 10px 12px; border: 1.5px solid var(--border-alt); border-radius: 10px; background: var(--input-bg); color: var(--text-primary); font-size: 13px; outline: none;">
            </div>
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">Periode Akhir *</label>
                <input type="date" name="periode_akhir" required style="width: 100%; padding: 10px 12px; border: 1.5px solid var(--border-alt); border-radius: 10px; background: var(--input-bg); color: var(--text-primary); font-size: 13px; outline: none;">
            </div>
        </div>
        
        @if($approvedJurusanReports->count() > 0)
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">Sertakan Laporan Jurusan (Opsional)</label>
                <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 10px; padding: 12px; max-height: 200px; overflow-y: auto;">
                    @foreach($approvedJurusanReports as $jurusanReport)
                        <div style="display: flex; align-items: center; gap: 8px; padding: 8px 0; border-bottom: 1px solid var(--border-subtle);">
                            <input type="checkbox" name="include_jurusan_reports[]" value="{{ $jurusanReport->id }}" id="jurusan_{{ $jurusanReport->id }}" style="width: 16px; height: 16px;">
                            <label for="jurusan_{{ $jurusanReport->id }}" style="flex: 1; font-size: 12px; color: var(--text-secondary); cursor: pointer;">
                                <span style="font-weight: 600; color: var(--text-primary);">{{ $jurusanReport->jurusan->nama ?? 'Unknown' }}</span>
                                <span style="color: var(--text-muted);">({{ $jurusanReport->periode_awal->format('d M Y') }} - {{ $jurusanReport->periode_akhir->format('d M Y') }})</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" style="padding: 10px 20px; background: var(--blue-dark); color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.15s;">
                Kirim ke Superadmin
            </button>
            <a href="{{ route('admin.laporan-admin') }}" style="padding: 10px 20px; background: var(--bg-card-subtle); color: var(--text-muted); border: 1px solid var(--border-subtle); border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none; cursor: pointer; transition: all 0.15s;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection