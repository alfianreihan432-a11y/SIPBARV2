@extends('layouts.guru')

@section('title', 'Ajukan Pengembalian Barang – SIPBAR')

@section('content')
<div style="max-width: 760px; margin: 0 auto;">
    {{-- Breadcrumb back link --}}
    <div style="margin-bottom: 16px;">
        <a href="{{ route('teacher.pengembalian-guru') }}" class="back-link-btn">
            &larr; Kembali ke Pengembalian
        </a>
    </div>

    @if ($errors->any())
        <div style="background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.3); color: #ef4444; padding: 14px 18px; border-radius: 10px; margin-bottom: 18px; font-size: 13px;">
            <div style="font-weight: 700; margin-bottom: 4px;">Terdapat kesalahan pada input Anda:</div>
            <ul style="padding-left: 18px; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-container-card">
        <div class="form-header-box">
            <div>
                <h1 class="form-main-title">Form Pengajuan Pengembalian</h1>
                <p class="form-sub-title">Lengkapi kondisi barang yang dikembalikan untuk diverifikasi oleh Kepala Jurusan</p>
            </div>
        </div>

        {{-- Ringkasan Barang yang Dipinjam (Card Box versi Siswa) --}}
        <div class="item-summary-box">
            <div>
                <div class="summary-label">Barang yang Dipinjam</div>
                <div class="summary-name">{{ $borrowing->item?->name ?? 'Barang #' . $borrowing->item_id }}</div>
                <div class="summary-meta">
                    Kode: <strong style="color: var(--text)">{{ $borrowing->item?->code ?? '-' }}</strong> &bull; Qty: <strong style="color: var(--text)">{{ $borrowing->quantity }} Unit</strong>
                </div>
            </div>
            <div class="summary-due">
                <div class="due-label">Jatuh Tempo:</div>
                <div class="due-date">
                    {{ $borrowing->return_date ? $borrowing->return_date->format('d M Y') : '-' }} {{ $borrowing->return_time ?? '' }}
                </div>
            </div>
        </div>

        <form action="{{ route('teacher.pengembalian-guru.store', $borrowing->id) }}" method="POST" enctype="multipart/form-data" id="returnForm">
            @csrf

            {{-- 1. Kondisi Barang (Card Selectable) --}}
            <div style="margin-bottom: 22px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 8px;">
                    Kondisi Fisik Barang Saat Dikembalikan <span style="color: #ef4444">*</span>
                </label>
                <div class="condition-grid">
                    @php
                        $conditions = [
                            [
                                'val' => 'baik',
                                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#10b981" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                'title' => 'Baik',
                                'sub' => 'Normal / Utuh'
                            ],
                            [
                                'val' => 'rusak_ringan',
                                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#f59e0b" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
                                'title' => 'Rusak Ringan',
                                'sub' => 'Gores / Lecet'
                            ],
                            [
                                'val' => 'rusak_berat',
                                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#ef4444" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                'title' => 'Rusak Berat',
                                'sub' => 'Patah / Mati Total'
                            ],
                            [
                                'val' => 'hilang',
                                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#64748b" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>',
                                'title' => 'Hilang',
                                'sub' => 'Tidak Ditemukan'
                            ],
                        ];
                    @endphp
                    @foreach($conditions as $c)
                    <label style="position: relative; cursor: pointer;">
                        <input type="radio" name="kondisi_barang" value="{{ $c['val'] }}" {{ old('kondisi_barang', 'baik') === $c['val'] ? 'checked' : '' }} style="position: absolute; opacity: 0; width: 0; height: 0;" class="condition-radio">
                        <div class="condition-box">
                            <div style="display:flex;align-items:center;justify-content:center;height:28px;">{!! $c['svg'] !!}</div>
                            <div class="cond-title">{{ $c['title'] }}</div>
                            <div class="cond-sub">{{ $c['sub'] }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- 2. Catatan Pengembalian dengan Karakter Limit & Counter --}}
            <div style="margin-bottom: 22px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <label for="catatan" style="font-size: 13px; font-weight: 700; color: var(--text); margin: 0;">
                        Catatan Pengembalian <span style="font-weight: normal; color: var(--muted);">(Opsional)</span>
                    </label>
                    <span id="charCounter" style="font-size: 11.5px; color: var(--muted); font-weight: 500;">
                        0 / 1000 karakter
                    </span>
                </div>
                <textarea 
                    name="catatan" 
                    id="catatan" 
                    rows="3" 
                    maxlength="1000"
                    class="custom-textarea"
                    placeholder="Tuliskan keterangan mengenai kondisi barang, kelengkapan aksesoris, atau kendala pemakaian...">{{ old('catatan', old('return_notes')) }}</textarea>
                <div style="font-size: 11px; color: var(--muted); margin-top: 4px;">Maksimal 1000 karakter. Jelaskan secara rinci jika ada kerusakan atau masalah dengan barang.</div>
            </div>

            {{-- 3. Upload Foto Bukti Kondisi Fisik (Drag & Drop / Klik) --}}
            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 8px;">
                    Foto Bukti Kondisi Fisik <span style="font-weight: normal; color: var(--muted);">(Opsional, Disarankan jika rusak)</span>
                </label>

                <div id="uploadZone" class="upload-dropzone">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 40px; height: 40px; color: var(--accent); margin: 0 auto 8px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <div style="font-size: 13px; font-weight: 700; color: var(--text);">Klik atau seret foto ke sini untuk mengunggah</div>
                    <div style="font-size: 11px; color: var(--muted); margin-top: 2px;">Format: JPG, JPEG, PNG, WEBP &bull; Maksimal 2MB</div>
                </div>

                <input type="file" name="foto_bukti" id="photoInput" accept="image/jpeg,image/png,image/webp" style="display: none;">

                {{-- Preview Container --}}
                <div id="previewWrapper" style="display: none; margin-top: 14px; position: relative; text-align: center;">
                    <img id="imagePreview" src="" alt="Preview Bukti" style="max-height: 220px; border-radius: 10px; border: 1px solid var(--border); box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
                    <button type="button" id="removePhotoBtn" title="Hapus foto" style="position: absolute; top: -8px; right: calc(50% - 110px); background: #ef4444; color: #fff; border: none; border-radius: 50%; width: 26px; height: 26px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <div style="font-size: 11px; color: var(--muted); margin-top: 6px;" id="fileNameDisplay"></div>
                </div>
            </div>

            {{-- 4. Actions (Batal & Kirim Pengajuan Pengembalian) --}}
            <div class="bottom-actions-row">
                <a href="{{ route('teacher.pengembalian-guru') }}" class="btn-cancel-custom">Batal</a>
                <button type="submit" class="btn-submit-custom">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Kirim Pengajuan Pengembalian
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .back-link-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text);
        text-decoration: none;
        transition: all 0.2s;
    }
    .back-link-btn:hover {
        background: var(--border);
        color: var(--text);
    }

    .form-container-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 28px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        color: var(--text);
    }

    .form-header-box {
        margin-bottom: 22px;
    }
    .form-main-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text);
        margin: 0 0 4px 0;
    }
    .form-sub-title {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    .item-summary-box {
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 16px 18px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .summary-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .summary-name {
        font-size: 16px;
        font-weight: 800;
        color: var(--text);
        margin-top: 2px;
    }
    .summary-meta {
        font-size: 12px;
        color: var(--muted);
        margin-top: 2px;
    }
    .summary-due {
        text-align: right;
    }
    .due-label {
        font-size: 11px;
        color: var(--muted);
    }
    .due-date {
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
    }

    .condition-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }
    @media (max-width: 640px) {
        .condition-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }

    .condition-box {
        border: 1.5px solid var(--border);
        border-radius: 10px;
        padding: 14px 10px;
        text-align: center;
        background: var(--bg3);
        transition: all .2s;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        cursor: pointer;
    }
    .condition-box:hover {
        border-color: #3b82f6;
    }
    .cond-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
    }
    .cond-sub {
        font-size: 10.5px;
        color: var(--muted);
    }

    /* Highlight border biru saat aktif seperti versi siswa */
    .condition-radio:checked + .condition-box {
        border-color: #2563eb !important;
        background: rgba(37, 99, 235, 0.08) !important;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.25) !important;
    }
    .condition-radio:checked + .condition-box .cond-title {
        color: #2563eb;
    }

    .custom-textarea {
        width: 100%;
        background: var(--input-bg);
        border: 1.5px solid var(--border);
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13.5px;
        color: var(--text);
        outline: none;
        font-family: inherit;
        box-sizing: border-box;
        transition: border-color 0.2s;
    }
    .custom-textarea:focus {
        border-color: #2563eb;
    }

    .upload-dropzone {
        border: 2px dashed var(--border);
        border-radius: 12px;
        padding: 24px 20px;
        text-align: center;
        cursor: pointer;
        background: var(--bg3);
        transition: all .2s;
    }
    .upload-dropzone:hover {
        border-color: #2563eb;
        background: rgba(37, 99, 235, 0.04);
    }

    .bottom-actions-row {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
    }

    .btn-cancel-custom {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 20px;
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: 9px;
        font-size: 13.5px;
        font-weight: 600;
        color: var(--text);
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-cancel-custom:hover {
        background: var(--border);
        color: var(--text);
    }

    .btn-submit-custom {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 22px;
        background: var(--accent);
        border: none;
        border-radius: 9px;
        font-size: 13.5px;
        font-weight: 700;
        color: #ffffff;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);
    }
    .btn-submit-custom:hover {
        opacity: 0.92;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Character counter
        const textarea = document.getElementById('catatan');
        const counter = document.getElementById('charCounter');
        if (textarea && counter) {
            function updateCounter() {
                const len = textarea.value.length;
                counter.textContent = `${len} / 1000 karakter`;
                if (len >= 950) {
                    counter.style.color = '#ef4444';
                    counter.style.fontWeight = '700';
                } else {
                    counter.style.color = 'var(--muted)';
                    counter.style.fontWeight = '500';
                }
            }
            textarea.addEventListener('input', updateCounter);
            updateCounter();
        }

        // Photo upload drag & drop + preview
        const photoInput = document.getElementById('photoInput');
        const uploadZone = document.getElementById('uploadZone');
        const previewWrapper = document.getElementById('previewWrapper');
        const imagePreview = document.getElementById('imagePreview');
        const removePhotoBtn = document.getElementById('removePhotoBtn');
        const fileNameDisplay = document.getElementById('fileNameDisplay');

        if (uploadZone && photoInput) {
            uploadZone.addEventListener('click', function () {
                photoInput.click();
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                uploadZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    uploadZone.style.borderColor = '#2563eb';
                    uploadZone.style.background = 'rgba(37, 99, 235, 0.08)';
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    uploadZone.style.borderColor = 'var(--border)';
                    uploadZone.style.background = 'var(--bg3)';
                }, false);
            });

            uploadZone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files.length > 0) {
                    photoInput.files = files;
                    showPreview(files[0]);
                }
            });

            photoInput.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    showPreview(this.files[0]);
                }
            });
        }

        function showPreview(file) {
            if (!file.type.match('image.*')) {
                alert('File yang dipilih harus berupa gambar (JPG, JPEG, PNG, WEBP).');
                photoInput.value = '';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal adalah 2MB.');
                photoInput.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                previewWrapper.style.display = 'block';
                uploadZone.style.display = 'none';
                fileNameDisplay.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            };
            reader.readAsDataURL(file);
        }

        if (removePhotoBtn) {
            removePhotoBtn.addEventListener('click', function () {
                photoInput.value = '';
                imagePreview.src = '';
                previewWrapper.style.display = 'none';
                uploadZone.style.display = 'block';
            });
        }
    });
</script>
@endsection