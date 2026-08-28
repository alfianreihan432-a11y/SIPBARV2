<div class="borrowing-form-container" style="background: var(--card); border-radius: 20px; border: 1px solid var(--border2); padding: 28px; box-shadow: 0 4px 24px rgba(0,0,0,0.06);">
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

        @media (max-width: 768px) {
            .borrowing-form-container { padding: 20px !important; border-radius: 16px !important; }
            .item-image { width: 70px !important; height: 70px !important; }
            .form-header { flex-direction: column; align-items: flex-start !important; gap: 16px !important; }
            .close-btn { position: absolute; top: 20px; right: 20px; }
            .dates-grid { grid-template-columns: 1fr !important; gap: 12px !important; }
            .student-data-grid { grid-template-columns: 1fr !important; gap: 12px !important; }
        }

        @media (max-width: 480px) {
            .borrowing-form-container { padding: 16px !important; }
            .form-title { font-size: 18px !important; }
            .submit-buttons { flex-direction: column !important; }
            .submit-buttons button { width: 100% !important; }
        }
    </style>

    {{-- Form Header --}}
    <div class="form-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; position: relative;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 42px; height: 42px; background: var(--primary-light); border: 1px solid var(--primary-muted); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px; color: var(--primary);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h2 style="font-family: var(--font-head); font-size: 20px; font-weight: 800; color: var(--text);">Form Pengajuan Peminjaman</h2>
        </div>
        <button type="button" wire:click="close" class="close-btn" style="background: var(--bg3); border: 1px solid var(--border2); cursor: pointer; color: var(--muted); padding: 8px; border-radius: 9px; transition: all .2s; display: flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Item Information --}}
    <div style="background: var(--bg3); border-radius: 14px; padding: 18px; margin-bottom: 20px; border: 1px solid var(--border2);">
        <div style="display: flex; align-items: flex-start; gap: 16px;">
            @if($item->photo_path)
                <img src="{{ asset('storage/' . $item->photo_path) }}" alt="{{ $item->name }}" class="item-image" style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px; border: 1px solid var(--border2);">
            @else
                <div class="item-image" style="width: 80px; height: 80px; background: var(--card); border: 1px solid var(--border2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 36px; height: 36px; color: var(--subtle);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            @endif
            <div style="flex: 1;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                    <h3 style="font-family: var(--font-head); font-weight: 700; color: var(--text); font-size: 15px;">{{ $item->name }}</h3>
                </div>
                <p style="font-size: 12px; color: var(--muted); margin-bottom: 2px;">{{ $item->category->name ?? 'Kategori Umum' }} &bull; Kode: <strong style="color: var(--text)">{{ $item->code }}</strong></p>
                <div style="display: flex; gap: 14px; margin-top: 6px; font-size: 12px; color: var(--muted);">
                    <div>Stok: <strong style="color: var(--text)">{{ $item->stock }}</strong> unit</div>
                    <div>Kondisi: <strong style="color: var(--text)">{{ $item->condition }}</strong></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Student Information (Auto-filled) --}}
    <div style="background: var(--card2); border-radius: 14px; padding: 18px; margin-bottom: 20px; border: 1px solid var(--border2);">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px; color: var(--primary);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <h4 style="font-size: 13px; font-weight: 700; color: var(--text);">Data Siswa (Peminjam)</h4>
        </div>
        <div class="student-data-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
            <div>
                <label style="display: block; font-size: 11px; color: var(--muted); margin-bottom: 4px; font-weight: 600;">Nama</label>
                <p style="font-weight: 600; color: var(--text); font-size: 13px; background: var(--bg3); padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border2);">{{ auth()->user()->name }}</p>
            </div>
            <div>
                <label style="display: block; font-size: 11px; color: var(--muted); margin-bottom: 4px; font-weight: 600;">NIS</label>
                <p style="font-weight: 600; color: var(--text); font-size: 13px; background: var(--bg3); padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border2);">{{ auth()->user()->nis ?? '-' }}</p>
            </div>
            <div>
                <label style="display: block; font-size: 11px; color: var(--muted); margin-bottom: 4px; font-weight: 600;">Kelas</label>
                <p style="font-weight: 600; color: var(--text); font-size: 13px; background: var(--bg3); padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border2);">{{ auth()->user()->kelas ?? '-' }}</p>
            </div>
            <div>
                <label style="display: block; font-size: 11px; color: var(--muted); margin-bottom: 4px; font-weight: 600;">Jurusan</label>
                <p style="font-weight: 600; color: var(--text); font-size: 13px; background: var(--bg3); padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border2);">{{ auth()->user()->jurusan ?? '-' }}</p>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="submit">
        {{-- Quantity --}}
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;">
                Jumlah Barang *
            </label>
            <input type="number" wire:model="quantity" min="1" max="{{ $item->stock }}" class="s-filter-input">
            @error('quantity') <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
            <p style="font-size: 11px; color: var(--subtle); margin-top: 4px;">Maksimal tersedia: {{ $item->stock }} unit</p>
        </div>

        {{-- Purpose --}}
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;">
                Keperluan Peminjaman *
            </label>
            <textarea wire:model="purpose" rows="3" class="s-filter-input" style="resize: vertical;" placeholder="Jelaskan keperluan peminjaman barang..."></textarea>
            @error('purpose') <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
        </div>

        {{-- Dates Section --}}
        <div style="background: var(--bg3); border-radius: 12px; padding: 16px; margin-bottom: 16px; border: 1px solid var(--border2);">
            <div class="dates-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 6px;">Tanggal Pinjam *</label>
                    <input type="date" wire:model="borrow_date" class="s-filter-input">
                    @error('borrow_date') <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 6px;">Tanggal Kembali *</label>
                    <input type="date" wire:model="return_date" class="s-filter-input">
                    @error('return_date') <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
                </div>
            </div>
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 6px;">Jam Kembali *</label>
                <input type="time" wire:model="return_time" class="s-filter-input">
                @error('return_time') <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Teacher Selection --}}
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;">
                Guru Penanggung Jawab *
            </label>
            <select wire:model="teacher_id" class="s-filter-input">
                <option value="">-- Pilih Guru --</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->jabatan ?? 'Guru' }})</option>
                @endforeach
            </select>
            @error('teacher_id') <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
        </div>

        {{-- Notes --}}
        <div style="margin-bottom: 24px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;">
                Catatan (Opsional)
            </label>
            <textarea wire:model="notes" rows="2" class="s-filter-input" style="resize: vertical;" placeholder="Catatan tambahan..."></textarea>
            @error('notes') <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
        </div>

        {{-- Submit Buttons --}}
        <div class="submit-buttons" style="display: flex; gap: 12px;">
            <button type="button" wire:click="close" class="s-btn s-btn--secondary" style="flex: 1; justify-content: center;">
                Batal
            </button>
            <button type="submit" class="s-btn s-btn--primary" style="flex: 1; justify-content: center;">
                Kirim Pengajuan
            </button>
        </div>
    </form>

    @if(session('success'))
        <div style="margin-top: 16px; padding: 12px 16px; background: var(--s-returned-bg); border: 1px solid var(--s-returned-bdr); border-radius: 10px; color: var(--s-returned); font-size: 13px; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif
</div>
