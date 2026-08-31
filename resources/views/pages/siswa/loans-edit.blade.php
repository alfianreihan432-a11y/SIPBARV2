@extends('layouts.siswa')

@section('title', 'Edit Peminjaman – SIPBAR')

@section('content')
<style>
    input[type="date"]::-webkit-calendar-picker-indicator,
    input[type="time"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        opacity: 0.6;
        transition: opacity 0.2s;
        filter: invert(0.5);
    }
    html.dark input[type="date"]::-webkit-calendar-picker-indicator,
    html.dark input[type="time"]::-webkit-calendar-picker-indicator {
        filter: invert(0.8);
    }
    input[type="date"]::-webkit-calendar-picker-indicator:hover,
    input[type="time"]::-webkit-calendar-picker-indicator:hover {
        opacity: 1;
    }
</style>

<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">Edit Peminjaman</div>
        <div class="page-subtitle">Perbarui detail pengajuan peminjaman yang masih menunggu persetujuan.</div>
    </div>
    <a href="{{ route('student.loans') }}" class="s-btn s-btn--ghost">← Kembali</a>
</div>

<div class="s-card" style="max-width: 900px; margin: 0 auto;">
    <div class="s-card-header">
        <div>
            <div class="s-card-title">Form Edit Permohonan</div>
            <div class="s-card-sub">Ubah tujuan, guru pembimbing, jadwal, dan jumlah pinjaman.</div>
        </div>
    </div>

    <form method="POST" action="{{ route('student.loans.update', $borrowing->id) }}" style="padding: 20px 18px 8px;">
        @csrf
        @method('PUT')

        {{-- Item Information --}}
        <div style="background: var(--bg3); border-radius: 14px; padding: 18px; margin-bottom: 20px; border: 1px solid var(--border2);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px; color: var(--primary);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <h4 style="font-size: 13px; font-weight: 700; color: var(--text);">Informasi Barang</h4>
            </div>
            <p style="font-weight: 600; color: var(--text); font-size: 14px; background: var(--card); padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border2);">{{ $borrowing->item?->name ?? 'Barang tidak tersedia' }}</p>
        </div>

        {{-- Quantity --}}
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;">
                Jumlah Barang *
            </label>
            <input type="number" name="quantity" value="{{ old('quantity', $borrowing->quantity) }}" min="1" class="s-filter-input" required>
            @error('quantity')
                <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>
            @enderror
        </div>

        {{-- Purpose --}}
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;">
                Keperluan Peminjaman *
            </label>
            <textarea name="purpose" rows="3" class="s-filter-input" style="resize: vertical;" required>{{ old('purpose', $borrowing->purpose) }}</textarea>
            @error('purpose')
                <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>
            @enderror
        </div>

        {{-- Dates Section --}}
        <div style="background: var(--bg3); border-radius: 12px; padding: 16px; margin-bottom: 16px; border: 1px solid var(--border2);">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 6px;">Tanggal Pinjam *</label>
                    <input type="date" name="borrow_date" value="{{ old('borrow_date', $borrowing->borrow_date?->format('Y-m-d')) }}" class="s-filter-input" required>
                    @error('borrow_date')
                        <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 6px;">Tanggal Kembali *</label>
                    <input type="date" name="return_date" value="{{ old('return_date', $borrowing->return_date?->format('Y-m-d')) }}" class="s-filter-input" required>
                    @error('return_date')
                        <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 6px;">Jam Kembali *</label>
                <input type="time" name="return_time" value="{{ old('return_time', $borrowing->return_time ?? '14:00') }}" class="s-filter-input" required>
                @error('return_time')
                    <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Teacher Selection --}}
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;">
                Guru Penanggung Jawab *
            </label>
            <select name="teacher_id" class="s-filter-input" required>
                <option value="">-- Pilih Guru --</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $borrowing->teacher_id) == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>
            @error('teacher_id')
                <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>
            @enderror
        </div>

        {{-- Notes --}}
        <div style="margin-bottom: 24px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;">
                Catatan (Opsional)
            </label>
            <textarea name="notes" rows="2" class="s-filter-input" style="resize: vertical;" placeholder="Catatan tambahan...">{{ old('notes', $borrowing->notes) }}</textarea>
            @error('notes')
                <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>
            @enderror
        </div>

        {{-- Error Message --}}
        @if($errors->any())
            <div style="margin-bottom:16px;padding:12px 16px;border-radius:10px;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);color:#ef4444;font-size:13px;display:flex;align-items:center;gap:8px;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Mohon periksa kembali data yang Anda masukkan.
            </div>
        @endif

        {{-- Submit Buttons --}}
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('student.loans') }}" class="s-btn s-btn--secondary" style="flex: 1; justify-content: center; text-decoration: none;">
                Batal
            </a>
            <button type="submit" class="s-btn s-btn--primary" style="flex: 1; justify-content: center;">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
