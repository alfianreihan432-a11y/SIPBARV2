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

    /* ============================================
       CUSTOM SEARCHABLE TEACHER DROPDOWN (Edit)
    ============================================ */
    .ts-wrapper { position: relative; font-family: var(--font-sans, sans-serif); }
    .ts-trigger {
        display: flex; align-items: center; justify-content: space-between;
        gap: 8px; padding: 10px 14px; background: var(--card);
        border: 1.5px solid var(--border2); border-radius: 10px;
        cursor: pointer; font-size: 13.5px; color: var(--text);
        transition: border-color .2s, box-shadow .2s; user-select: none; min-height: 42px;
    }
    .ts-trigger:focus-visible { outline: 2px solid var(--primary); outline-offset: 2px; }
    .ts-trigger.ts-open {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(59,130,246,.15);
    }
    .ts-trigger-placeholder { color: var(--muted); }
    .ts-trigger-value { display: flex; align-items: center; gap: 8px; flex: 1; overflow: hidden; }
    .ts-trigger-value .ts-avatar {
        width: 26px; height: 26px; border-radius: 50%; background: var(--primary-light);
        border: 1.5px solid var(--primary-muted); display: flex; align-items: center;
        justify-content: center; font-size: 10px; font-weight: 700; color: var(--primary); flex-shrink: 0;
    }
    .ts-trigger-value .ts-val-name { font-weight: 600; color: var(--text); font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ts-trigger-value .ts-val-jabatan { font-size: 11px; color: var(--muted); flex-shrink: 0; }
    .ts-chevron { flex-shrink: 0; transition: transform .25s; color: var(--muted); }
    .ts-trigger.ts-open .ts-chevron { transform: rotate(180deg); }
    [x-cloak] { display: none !important; }
    .ts-dropdown {
        position: absolute; top: calc(100% + 6px); left: 0; right: 0;
        background: var(--card); border: 1.5px solid var(--border2); border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0,0,0,.15), 0 2px 8px rgba(0,0,0,.08);
        z-index: 99999; overflow: hidden;
        animation: tsDropIn .18s cubic-bezier(.22,.68,0,1.2);
    }
    @keyframes tsDropIn {
        from { opacity: 0; transform: translateY(-6px) scale(.98); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .ts-search-box {
        padding: 10px 12px; border-bottom: 1px solid var(--border2);
        display: flex; align-items: center; gap: 8px;
    }
    .ts-search-box svg { color: var(--muted); flex-shrink: 0; }
    .ts-search-input {
        flex: 1; border: none; outline: none; background: transparent;
        font-size: 13px; color: var(--text); font-family: inherit;
    }
    .ts-search-input::placeholder { color: var(--muted); }
    .ts-options {
        max-height: 248px; overflow-y: auto; padding: 6px;
        scrollbar-width: thin; scrollbar-color: var(--border2) transparent;
    }
    .ts-options::-webkit-scrollbar { width: 5px; }
    .ts-options::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 4px; }
    .ts-option {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 10px; border-radius: 8px; cursor: pointer; transition: background .15s;
    }
    .ts-option:hover, .ts-option.ts-focused { background: var(--bg3, rgba(0,0,0,.04)); }
    .ts-option.ts-selected { background: var(--primary-light); }
    .ts-option .ts-opt-avatar {
        width: 30px; height: 30px; border-radius: 50%; background: var(--primary-light);
        border: 1.5px solid var(--primary-muted); display: flex; align-items: center;
        justify-content: center; font-size: 11px; font-weight: 700; color: var(--primary); flex-shrink: 0;
    }
    .ts-option.ts-selected .ts-opt-avatar { background: var(--primary); color: #fff; border-color: var(--primary); }
    .ts-option .ts-opt-info { flex: 1; overflow: hidden; }
    .ts-option .ts-opt-name { font-weight: 600; font-size: 13px; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ts-option .ts-opt-jabatan { font-size: 11px; color: var(--muted); margin-top: 1px; }
    .ts-option.ts-selected .ts-opt-name { color: var(--primary); }
    .ts-check { flex-shrink: 0; opacity: 0; transition: opacity .15s; color: var(--primary); }
    .ts-option.ts-selected .ts-check { opacity: 1; }
    .ts-empty { padding: 20px 12px; text-align: center; color: var(--muted); font-size: 13px; display: none; }
    .ts-empty svg { display: block; margin: 0 auto 8px; color: var(--subtle); }
    .ts-native { display: none !important; }
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

        {{-- Teacher Selection (Custom Searchable Dropdown via Alpine.js) --}}
        <div style="margin-bottom: 16px;"
             x-data="{
                 open: false,
                 search: '',
                 selectedId: {{ (int) old('teacher_id', $borrowing->teacher_id ?? 0) }},
                 teachers: {{ Js::from($teachers->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'jabatan' => $t->jabatan ?? 'Guru'])) }},
                 get selectedTeacher() {
                     return this.teachers.find(t => t.id == this.selectedId);
                 },
                 get filteredTeachers() {
                     if (!this.search || !this.search.trim()) return this.teachers;
                     let q = this.search.toLowerCase().trim();
                     return this.teachers.filter(t => (t.name + ' ' + t.jabatan).toLowerCase().includes(q));
                 },
                 getInitials(name) {
                     if (!name) return 'G';
                     return name.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase();
                 },
                 select(teacher) {
                     this.selectedId = teacher.id;
                     this.open = false;
                     this.search = '';
                 }
             }">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;">
                Guru Penanggung Jawab *
            </label>

            {{-- Hidden input drives standard form POST/PUT --}}
            <input type="hidden" name="teacher_id" :value="selectedId" required>

            {{-- Custom dropdown wrapper --}}
            <div class="ts-wrapper" @click.outside="open = false" @keydown.escape.window="open = false" style="position: relative;">
                {{-- Trigger Button --}}
                <div @click="open = !open; if(open) { $nextTick(() => { $refs.searchInput && $refs.searchInput.focus() }); }"
                     :class="{ 'ts-open': open }"
                     class="ts-trigger"
                     tabindex="0"
                     role="combobox"
                     aria-haspopup="listbox"
                     :aria-expanded="open.toString()">
                    <div class="ts-trigger-value">
                        <template x-if="selectedTeacher">
                            <div style="display: flex; align-items: center; gap: 8px; overflow: hidden; width: 100%;">
                                <div class="ts-avatar" x-text="getInitials(selectedTeacher.name)"></div>
                                <span class="ts-val-name" x-text="selectedTeacher.name"></span>
                                <span class="ts-val-jabatan" x-text="selectedTeacher.jabatan"></span>
                            </div>
                        </template>
                        <template x-if="!selectedTeacher">
                            <span class="ts-trigger-placeholder">Cari atau pilih guru...</span>
                        </template>
                    </div>
                    <svg class="ts-chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </div>

                {{-- Dropdown Menu --}}
                <div x-show="open"
                     x-cloak
                     class="ts-dropdown ts-active"
                     role="listbox">
                    <div class="ts-search-box">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                        </svg>
                        <input x-ref="searchInput"
                               x-model="search"
                               class="ts-search-input"
                               type="text"
                               placeholder="Ketik nama guru..."
                               autocomplete="off"
                               spellcheck="false">
                    </div>

                    <div class="ts-options">
                        <template x-for="teacher in filteredTeachers" :key="teacher.id">
                            <div @click="select(teacher)"
                                 class="ts-option"
                                 :class="{ 'ts-selected': selectedId == teacher.id }"
                                 role="option">
                                <div class="ts-opt-avatar" x-text="getInitials(teacher.name)"></div>
                                <div class="ts-opt-info">
                                    <div class="ts-opt-name" x-text="teacher.name"></div>
                                    <div class="ts-opt-jabatan" x-text="teacher.jabatan"></div>
                                </div>
                                <svg class="ts-check" xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </div>
                        </template>

                        <template x-if="filteredTeachers.length === 0">
                            <div class="ts-empty" style="display: block;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Guru tidak ditemukan
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            @error('teacher_id')
                <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 6px;">{{ $message }}</span>
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
