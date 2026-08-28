<div class="borrowing-form-container" style="background: var(--card); border-radius: 20px; border: 1px solid var(--border2); padding: 28px; box-shadow: 0 4px 24px rgba(0,0,0,0.06);">
    <style>
        /* Custom Date Picker Styling */
        input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.2s;
        }
        input[type="date"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }
        input[type="time"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.2s;
        }
        input[type="time"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .borrowing-form-container {
                padding: 20px !important;
                border-radius: 16px !important;
            }
            .item-image {
                width: 70px !important;
                height: 70px !important;
            }
            .form-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 16px !important;
            }
            .close-btn {
                position: absolute;
                top: 20px;
                right: 20px;
            }
            .dates-grid {
                grid-template-columns: 1fr !important;
                gap: 12px !important;
            }
            .student-data-grid {
                grid-template-columns: 1fr !important;
                gap: 12px !important;
            }
        }

        @media (max-width: 480px) {
            .borrowing-form-container {
                padding: 16px !important;
            }
            .form-title {
                font-size: 18px !important;
            }
            .input-field {
                padding: 10px 14px !important;
                font-size: 13px !important;
            }
            .submit-buttons {
                flex-direction: column !important;
            }
            .submit-buttons button {
                width: 100% !important;
            }
        }
    </style>
    <div class="form-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; position: relative;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 44px; height: 44px; background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 22px; height: 22px; color: #fff;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h2 style="font-size: 22px; font-weight: 800; color: var(--text);">Form Pengajuan Peminjaman</h2>
        </div>
        <button type="button" wire:click="close" class="close-btn" style="background: var(--bg3); border: 1px solid var(--border2); cursor: pointer; color: var(--subtle); padding: 10px; border-radius: 10px; transition: all .2s; display: flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Item Information --}}
    <div style="background: linear-gradient(135deg, rgba(29, 78, 216, 0.08) 0%, rgba(37, 99, 235, 0.05) 100%); border-radius: 14px; padding: 20px; margin-bottom: 24px; border: 1px solid rgba(29, 78, 216, 0.15); box-shadow: 0 2px 12px rgba(29, 78, 216, 0.08);">
        <div style="display: flex; align-items: flex-start; gap: 18px;">
            @if($item->photo_path)
                <img src="{{ asset('storage/' . $item->photo_path) }}" alt="{{ $item->name }}" class="item-image" style="width: 90px; height: 90px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            @else
                <div class="item-image" style="width: 90px; height: 90px; background: var(--card); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 40px; height: 40px; color: var(--subtle);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            @endif
            <div style="flex: 1;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px; color: #1d4ed8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h3 style="font-weight: 700; color: var(--text); font-size: 16px;">{{ $item->name }}</h3>
                </div>
                <p style="font-size: 13px; color: var(--muted); margin-bottom: 4px;">{{ $item->category->name ?? 'Uncategorized' }}</p>
                <p style="font-size: 13px; color: var(--muted); margin-bottom: 8px;">Kode: {{ $item->code }}</p>
                <div style="display: flex; gap: 16px;">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 14px; height: 14px; color: var(--muted);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span style="font-size: 13px; color: var(--muted); font-weight: 600;">Stok: {{ $item->stock }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 14px; height: 14px; color: var(--muted);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span style="font-size: 13px; color: var(--muted); font-weight: 600;">Kondisi: {{ $item->condition }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Student Information (Auto-filled) --}}
    <div style="background: var(--bg3); border-radius: 14px; padding: 20px; margin-bottom: 24px; border: 1px solid var(--border2); box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px; color: #1d4ed8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <h4 style="font-weight: 700; color: var(--text); font-size: 15px;">Data Siswa (Otomatis)</h4>
        </div>
        <div class="student-data-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px;">
            <div>
                <label style="display: block; font-size: 12px; color: var(--muted); margin-bottom: 6px; font-weight: 500;">Nama</label>
                <p style="font-weight: 600; color: var(--text); font-size: 14px; background: var(--card); padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border2);">{{ auth()->user()->name }}</p>
            </div>
            <div>
                <label style="display: block; font-size: 12px; color: var(--muted); margin-bottom: 6px; font-weight: 500;">NIS</label>
                <p style="font-weight: 600; color: var(--text); font-size: 14px; background: var(--card); padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border2);">{{ auth()->user()->nis }}</p>
            </div>
            <div>
                <label style="display: block; font-size: 12px; color: var(--muted); margin-bottom: 6px; font-weight: 500;">Kelas</label>
                <p style="font-weight: 600; color: var(--text); font-size: 14px; background: var(--card); padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border2);">{{ auth()->user()->kelas }}</p>
            </div>
            <div>
                <label style="display: block; font-size: 12px; color: var(--muted); margin-bottom: 6px; font-weight: 500;">Jurusan</label>
                <p style="font-weight: 600; color: var(--text); font-size: 14px; background: var(--card); padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border2);">{{ auth()->user()->jurusan }}</p>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="submit">
        {{-- Quantity --}}
        <div style="margin-bottom: 20px;">
            <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; color: var(--text); margin-bottom: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px; color: #1d4ed8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                </svg>
                Jumlah *
            </label>
            <input type="number" wire:model="quantity" min="1" max="{{ $item->stock }}" 
                   style="width: 100%; padding: 12px 16px; border: 1px solid var(--border2); border-radius: 12px; background: var(--input-bg); color: var(--text); font-size: 14px; transition: all .2s; box-shadow: 0 1px 3px rgba(0,0,0,0.04);"
                   onfocus="this.style.borderColor='#1d4ed8'; this.style.boxShadow='0 0 0 3px rgba(29,78,216,0.1)'"
                   onblur="this.style.borderColor='var(--border2)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.04)'">
            @error('quantity') <span style="color: #ef4444; font-size: 12px; display: block; margin-top: 6px;">{{ $message }}</span> @enderror
            <p style="font-size: 12px; color: var(--subtle); margin-top: 6px;">Maksimal: {{ $item->stock }}</p>
        </div>

        {{-- Purpose --}}
        <div style="margin-bottom: 20px;">
            <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; color: var(--text); margin-bottom: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px; color: #1d4ed8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Keperluan Peminjaman *
            </label>
            <textarea wire:model="purpose" rows="3" 
                      style="width: 100%; padding: 12px 16px; border: 1px solid var(--border2); border-radius: 12px; background: var(--input-bg); color: var(--text); font-size: 14px; resize: vertical; transition: all .2s; box-shadow: 0 1px 3px rgba(0,0,0,0.04);"
                      placeholder="Jelaskan keperluan peminjaman barang..."
                      onfocus="this.style.borderColor='#1d4ed8'; this.style.boxShadow='0 0 0 3px rgba(29,78,216,0.1)'"
                      onblur="this.style.borderColor='var(--border2)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.04)'"></textarea>
            @error('purpose') <span style="color: #ef4444; font-size: 12px; display: block; margin-top: 6px;">{{ $message }}</span> @enderror
        </div>

        {{-- Dates Section --}}
        <div style="background: var(--bg3); border-radius: 14px; padding: 20px; margin-bottom: 20px; border: 1px solid var(--border2);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px; color: #1d4ed8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h4 style="font-weight: 700; color: var(--text); font-size: 15px;">Tanggal Peminjaman</h4>
            </div>
            <div class="dates-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px;">Tanggal Pinjam *</label>
                    <input type="date" wire:model="borrow_date" 
                           style="width: 100%; padding: 12px 16px; border: 1px solid var(--border2); border-radius: 12px; background: var(--input-bg); color: var(--text); font-size: 14px; transition: all .2s; box-shadow: 0 1px 3px rgba(0,0,0,0.04);"
                           onfocus="this.style.borderColor='#1d4ed8'; this.style.boxShadow='0 0 0 3px rgba(29,78,216,0.1)'"
                           onblur="this.style.borderColor='var(--border2)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.04)'">
                    @error('borrow_date') <span style="color: #ef4444; font-size: 12px; display: block; margin-top: 6px;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px;">Tanggal Kembali *</label>
                    <input type="date" wire:model="return_date" 
                           style="width: 100%; padding: 12px 16px; border: 1px solid var(--border2); border-radius: 12px; background: var(--input-bg); color: var(--text); font-size: 14px; transition: all .2s; box-shadow: 0 1px 3px rgba(0,0,0,0.04);"
                           onfocus="this.style.borderColor='#1d4ed8'; this.style.boxShadow='0 0 0 3px rgba(29,78,216,0.1)'"
                           onblur="this.style.borderColor='var(--border2)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.04)'">
                    @error('return_date') <span style="color: #ef4444; font-size: 12px; display: block; margin-top: 6px;">{{ $message }}</span> @enderror
                </div>
            </div>
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px;">Jam Kembali *</label>
                <input type="time" wire:model="return_time" 
                       style="width: 100%; padding: 12px 16px; border: 1px solid var(--border2); border-radius: 12px; background: var(--input-bg); color: var(--text); font-size: 14px; transition: all .2s; box-shadow: 0 1px 3px rgba(0,0,0,0.04);"
                       onfocus="this.style.borderColor='#1d4ed8'; this.style.boxShadow='0 0 0 3px rgba(29,78,216,0.1)'"
                       onblur="this.style.borderColor='var(--border2)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.04)'">
                @error('return_time') <span style="color: #ef4444; font-size: 12px; display: block; margin-top: 6px;">{{ $message }}</span> @enderror
                <p style="font-size: 12px; color: var(--subtle); margin-top: 6px;">Tentukan jam spesifik untuk pengembalian barang</p>
            </div>
        </div>

        {{-- Teacher Selection --}}
        <div style="margin-bottom: 20px;">
            <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; color: var(--text); margin-bottom: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px; color: #1d4ed8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Guru Penanggung Jawab *
            </label>
            <select wire:model="teacher_id" 
                    style="width: 100%; padding: 12px 16px; border: 1px solid var(--border2); border-radius: 12px; background: var(--input-bg); color: var(--text); font-size: 14px; transition: all .2s; box-shadow: 0 1px 3px rgba(0,0,0,0.04);"
                    onfocus="this.style.borderColor='#1d4ed8'; this.style.boxShadow='0 0 0 3px rgba(29,78,216,0.1)'"
                    onblur="this.style.borderColor='var(--border2)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.04)'">
                <option value="">Pilih Guru</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->jabatan ?? 'Guru' }})</option>
                @endforeach
            </select>
            @error('teacher_id') <span style="color: #ef4444; font-size: 12px; display: block; margin-top: 6px;">{{ $message }}</span> @enderror
        </div>

        {{-- Notes --}}
        <div style="margin-bottom: 28px;">
            <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; color: var(--text); margin-bottom: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px; color: var(--muted);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Catatan (Opsional)
            </label>
            <textarea wire:model="notes" rows="2" 
                      style="width: 100%; padding: 12px 16px; border: 1px solid var(--border2); border-radius: 12px; background: var(--input-bg); color: var(--text); font-size: 14px; resize: vertical; transition: all .2s; box-shadow: 0 1px 3px rgba(0,0,0,0.04);"
                      placeholder="Catatan tambahan..."
                      onfocus="this.style.borderColor='#1d4ed8'; this.style.boxShadow='0 0 0 3px rgba(29,78,216,0.1)'"
                      onblur="this.style.borderColor='var(--border2)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.04)'"></textarea>
            @error('notes') <span style="color: #ef4444; font-size: 12px; display: block; margin-top: 6px;">{{ $message }}</span> @enderror
        </div>

        {{-- Submit Button --}}
        <div class="submit-buttons" style="display: flex; gap: 14px;">
            <button type="button" wire:click="close"
                    style="flex: 1; padding: 14px 20px; border: 1px solid var(--border2); border-radius: 12px; color: var(--text); font-weight: 600; font-size: 14px; cursor: pointer; background: var(--bg3); transition: all .2s; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                Batal
            </button>
            <button type="submit"
                    style="flex: 1; padding: 14px 20px; background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%); color: #fff; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; border: none; transition: all .2s; box-shadow: 0 4px 12px rgba(29, 78, 216, 0.3);">
                Kirim Pengajuan
            </button>
        </div>
    </form>

    @if(session('success'))
        <div style="margin-top: 20px; padding: 14px 18px; background: rgba(5, 150, 105, 0.1); border: 1px solid rgba(5, 150, 105, 0.2); border-radius: 12px; color: #059669; font-size: 14px; display: flex; align-items: center; gap: 10px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif
</div>
