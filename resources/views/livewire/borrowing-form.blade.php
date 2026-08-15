<div style="background: var(--card); border-radius: 16px; border: 1px solid var(--border2); padding: 24px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
        <h2 style="font-size: 20px; font-weight: 800; color: var(--text);">Form Pengajuan Peminjaman</h2>
        <button type="button" wire:click="close" style="background: none; border: none; cursor: pointer; color: var(--subtle); padding: 8px; border-radius: 8px; transition: background .2s;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Item Information --}}
    <div style="background: rgba(29, 78, 216, 0.05); border-radius: 12px; padding: 16px; margin-bottom: 20px; border: 1px solid rgba(29, 78, 216, 0.1);">
        <div style="display: flex; align-items: flex-start; gap: 16px;">
            @if($item->photo_path)
                <img src="{{ asset('storage/' . $item->photo_path) }}" alt="{{ $item->name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px;">
            @else
                <div style="width: 80px; height: 80px; background: var(--bg3); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 40px; height: 40px; color: var(--subtle);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            @endif
            <div style="flex: 1;">
                <h3 style="font-weight: 700; color: var(--text); margin-bottom: 4px;">{{ $item->name }}</h3>
                <p style="font-size: 13px; color: var(--muted);">{{ $item->category->name ?? 'Uncategorized' }}</p>
                <p style="font-size: 13px; color: var(--muted);">Kode: {{ $item->code }}</p>
                <div style="display: flex; gap: 12px; margin-top: 4px;">
                    <span style="font-size: 13px; color: var(--muted);">Stok: {{ $item->stock }}</span>
                    <span style="font-size: 13px; color: var(--muted);">Kondisi: {{ $item->condition }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Student Information (Auto-filled) --}}
    <div style="background: var(--bg3); border-radius: 12px; padding: 16px; margin-bottom: 20px; border: 1px solid var(--border2);">
        <h4 style="font-weight: 700; color: var(--text); margin-bottom: 12px; font-size: 14px;">Data Siswa (Otomatis)</h4>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
            <div>
                <label style="display: block; font-size: 12px; color: var(--muted); margin-bottom: 4px;">Nama</label>
                <p style="font-weight: 600; color: var(--text); font-size: 13px;">{{ auth()->user()->name }}</p>
            </div>
            <div>
                <label style="display: block; font-size: 12px; color: var(--muted); margin-bottom: 4px;">NIS</label>
                <p style="font-weight: 600; color: var(--text); font-size: 13px;">{{ auth()->user()->nis }}</p>
            </div>
            <div>
                <label style="display: block; font-size: 12px; color: var(--muted); margin-bottom: 4px;">Kelas</label>
                <p style="font-weight: 600; color: var(--text); font-size: 13px;">{{ auth()->user()->kelas }}</p>
            </div>
            <div>
                <label style="display: block; font-size: 12px; color: var(--muted); margin-bottom: 4px;">Jurusan</label>
                <p style="font-weight: 600; color: var(--text); font-size: 13px;">{{ auth()->user()->jurusan }}</p>
            </div>
        </div>
    </div>

    <form wire:submit="submit">
        {{-- Quantity --}}
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px;">Jumlah *</label>
            <input type="number" wire:model="quantity" min="1" max="{{ $item->stock }}" 
                   style="width: 100%; padding: 10px 14px; border: 1px solid var(--border2); border-radius: 10px; background: var(--input-bg); color: var(--text); font-size: 13px;">
            @error('quantity') <span style="color: #ef4444; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
            <p style="font-size: 11px; color: var(--subtle); margin-top: 4px;">Maksimal: {{ $item->stock }}</p>
        </div>

        {{-- Purpose --}}
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px;">Keperluan Peminjaman *</label>
            <textarea wire:model="purpose" rows="3" 
                      style="width: 100%; padding: 10px 14px; border: 1px solid var(--border2); border-radius: 10px; background: var(--input-bg); color: var(--text); font-size: 13px; resize: vertical;"
                      placeholder="Jelaskan keperluan peminjaman barang..."></textarea>
            @error('purpose') <span style="color: #ef4444; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
        </div>

        {{-- Dates --}}
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px;">Tanggal Pinjam *</label>
                <input type="date" wire:model="borrow_date" 
                       style="width: 100%; padding: 10px 14px; border: 1px solid var(--border2); border-radius: 10px; background: var(--input-bg); color: var(--text); font-size: 13px;">
                @error('borrow_date') <span style="color: #ef4444; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px;">Tanggal Kembali *</label>
                <input type="date" wire:model="return_date" 
                       style="width: 100%; padding: 10px 14px; border: 1px solid var(--border2); border-radius: 10px; background: var(--input-bg); color: var(--text); font-size: 13px;">
                @error('return_date') <span style="color: #ef4444; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Teacher Selection --}}
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px;">Guru Penanggung Jawab *</label>
            <select wire:model="teacher_id" 
                    style="width: 100%; padding: 10px 14px; border: 1px solid var(--border2); border-radius: 10px; background: var(--input-bg); color: var(--text); font-size: 13px;">
                <option value="">Pilih Guru</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->jabatan ?? 'Guru' }})</option>
                @endforeach
            </select>
            @error('teacher_id') <span style="color: #ef4444; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
        </div>

        {{-- Notes --}}
        <div style="margin-bottom: 24px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px;">Catatan (Opsional)</label>
            <textarea wire:model="notes" rows="2" 
                      style="width: 100%; padding: 10px 14px; border: 1px solid var(--border2); border-radius: 10px; background: var(--input-bg); color: var(--text); font-size: 13px; resize: vertical;"
                      placeholder="Catatan tambahan..."></textarea>
            @error('notes') <span style="color: #ef4444; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
        </div>

        {{-- Submit Button --}}
        <div style="display: flex; gap: 12px;">
            <button type="button" wire:click="close"
                    style="flex: 1; padding: 12px 16px; border: 1px solid var(--border2); border-radius: 10px; color: var(--text); font-weight: 600; font-size: 13px; cursor: pointer; background: var(--bg3); transition: background .2s;">
                Batal
            </button>
            <button type="submit" 
                    style="flex: 1; padding: 12px 16px; background: #1d4ed8; color: #fff; border-radius: 10px; font-weight: 600; font-size: 13px; cursor: pointer; border: none; transition: background .2s;">
                Kirim Pengajuan
            </button>
        </div>
    </form>

    @if(session('success'))
        <div style="margin-top: 16px; padding: 12px 16px; background: rgba(5, 150, 105, 0.1); border: 1px solid rgba(5, 150, 105, 0.2); border-radius: 10px; color: #059669; font-size: 13px;">
            {{ session('success') }}
        </div>
    @endif
</div>
