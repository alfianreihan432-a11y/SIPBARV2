@extends('layouts.admin')

@section('title', 'Scan QR Siswa – SIPBAR')
@section('page-heading', 'Scan QR Siswa')

@section('content')
<style>
    .qr-scanner-card {
        max-width: 560px;
        margin: 20px auto;
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 16px;
        padding: 28px 24px;
        box-shadow: var(--card-shadow);
        color: var(--text-primary);
    }
    .qr-viewport-wrap {
        position: relative;
        width: 100%;
        max-width: 420px;
        margin: 0 auto 18px;
        border-radius: 14px;
        overflow: hidden;
        background: #000000;
        border: 2px solid var(--border-alt);
        aspect-ratio: 1 / 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #qr-reader {
        width: 100% !important;
        height: 100% !important;
        border: none !important;
    }
    #qr-reader video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        border-radius: 12px;
    }
    .qr-placeholder {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
        text-align: center;
        background: var(--bg-card-subtle);
        color: var(--text-muted);
        z-index: 5;
    }
    .qr-success-overlay {
        position: absolute;
        inset: 0;
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: rgba(16, 185, 129, 0.95);
        color: #ffffff;
        z-index: 20;
        text-align: center;
        padding: 20px;
    }
    .qr-alert {
        display: none;
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 13px;
        line-height: 1.5;
        margin-bottom: 16px;
    }
    .qr-alert-danger {
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid #ef4444;
        color: #ef4444;
    }
    .qr-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
    }
    .qr-btn-primary {
        background: var(--blue);
        color: #ffffff;
    }
    .qr-btn-primary:hover {
        background: var(--blue-dark);
    }
    .qr-btn-secondary {
        background: var(--bg-hover);
        color: var(--text-primary);
        border: 1px solid var(--border-alt);
    }
    .qr-btn-secondary:hover {
        background: var(--border-alt);
    }
    .qr-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 24px 0 20px;
        color: var(--text-subtle);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .qr-divider::before, .qr-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border-subtle);
    }
    .qr-input-manual {
        width: 100%;
        padding: 11px 14px;
        background: var(--input-bg);
        border: 1.5px solid var(--input-border);
        border-radius: 10px;
        color: var(--text-primary);
        font-size: 13.5px;
        outline: none;
        transition: border-color .2s;
    }
    .qr-input-manual:focus {
        border-color: var(--blue);
    }
</style>

<div class="qr-scanner-card">
    <div style="text-align: center; margin-bottom: 20px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(59,130,246,0.12); color: var(--blue); display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-5v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V8a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1zm12 0h2a1 1 0 001-1V8a1 1 0 00-1-1h-2a1 1 0 00-1 1v1a1 1 0 001 1zM5 20h2a1 1 0 001-1v-1a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1z"/>
            </svg>
        </div>
        <h2 style="font-size: 18px; font-weight: 800; margin-bottom: 4px;">Pindai QR Code Siswa</h2>
        <p style="font-size: 13px; color: var(--text-muted); margin: 0;">Arahkan kamera ke QR Code siswa untuk verifikasi pengambilan barang</p>
    </div>

    {{-- Error Banner --}}
    <div id="camera-error-banner" class="qr-alert qr-alert-danger">
        <div style="display:flex;align-items:flex-start;gap:8px">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;margin-top:1px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div id="camera-error-msg">Akses kamera tidak tersedia. Silakan periksa izin browser atau gunakan input manual di bawah.</div>
        </div>
    </div>

    {{-- Viewport Kamera --}}
    <div class="qr-viewport-wrap">
        <div id="qr-reader"></div>

        {{-- Placeholder Sebelum Kamera Dimulai --}}
        <div id="camera-placeholder" class="qr-placeholder">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:42px;height:42px;color:var(--text-subtle);margin-bottom:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <div style="font-size:13.5px;font-weight:600;color:var(--text-primary);margin-bottom:4px;">Kamera Siap Digunakan</div>
            <div style="font-size:12px;color:var(--text-muted);margin-bottom:14px;max-width:280px;">Nyalakan kamera untuk scan langsung, atau upload foto/gambar QR Code dari perangkat.</div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;justify-content:center;">
                <button type="button" onclick="startCamera()" class="qr-btn qr-btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Nyalakan Kamera
                </button>
                <button type="button" onclick="triggerFileInput()" class="qr-btn qr-btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Upload Foto QR
                </button>
            </div>
        </div>

        {{-- Overlay Sukses Scan / Analisis Gambar --}}
        <div id="scan-success-overlay" class="qr-success-overlay">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:52px;height:52px;margin-bottom:10px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
            <div id="scan-success-title" style="font-size:16px;font-weight:800;margin-bottom:4px;">QR Berhasil Terbaca!</div>
            <div id="scan-success-sub" style="font-size:12.5px;opacity:0.9;">Mengalihkan ke halaman verifikasi...</div>
        </div>
    </div>

    {{-- Input File Tersembunyi untuk Upload Foto --}}
    <input type="file" id="qr-file-input" accept="image/*" style="display:none" onchange="handleFileUpload(event)">

    {{-- Tombol Kontrol Kamera --}}
    <div id="camera-controls" style="display:none;justify-content:center;gap:10px;margin-bottom:16px;flex-wrap:wrap;">
        <button type="button" onclick="stopCamera()" class="qr-btn qr-btn-secondary" style="font-size:12.5px;padding:8px 14px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;color:#ef4444" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
            Matikan Kamera
        </button>
        <button type="button" id="btn-switch-camera" onclick="switchCamera()" class="qr-btn qr-btn-secondary" style="display:none;font-size:12.5px;padding:8px 14px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Ganti Kamera
        </button>
        <button type="button" onclick="triggerFileInput()" class="qr-btn qr-btn-secondary" style="font-size:12.5px;padding:8px 14px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Upload Foto QR
        </button>
    </div>

    {{-- Divider Fallback Manual --}}
    <div class="qr-divider">Atau Masukkan Kode Manual</div>

    {{-- Form Input Manual --}}
    <form onsubmit="handleManualSubmit(event)">
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            <input 
                type="text" 
                id="manualTokenInput" 
                class="qr-input-manual" 
                style="flex: 1; min-width: 220px;"
                placeholder="Tempel URL QR atau ketik kode token..." 
                autocomplete="off"
            >
            <button type="submit" class="qr-btn qr-btn-primary" style="white-space:nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Verifikasi
            </button>
        </div>
        <div style="font-size:11.5px;color:var(--text-muted);margin-top:6px;">
            Dapat berupa kode token (misal: <code>32 karakter / UUID</code>) atau URL lengkap hasil scan scanner eksternal.
        </div>
    </form>
</div>

{{-- Load html5-qrcode dan jsQR dari CDN --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

<script>
var html5QrCode = null;
var isScanning = false;
var currentFacingMode = "environment";

function extractToken(input) {
    if (!input) return '';
    var text = input.trim();
    if (text.includes('/')) {
        var cleanUrl = text.split('?')[0].split('#')[0];
        var parts = cleanUrl.split('/').filter(Boolean);
        return parts[parts.length - 1];
    }
    return text;
}

function showCameraError(msg) {
    var banner = document.getElementById('camera-error-banner');
    var msgEl = document.getElementById('camera-error-msg');
    if (banner && msgEl) {
        msgEl.textContent = msg;
        banner.style.display = 'block';
    }
}

function hideCameraError() {
    var banner = document.getElementById('camera-error-banner');
    if (banner) banner.style.display = 'none';
}

function onScanSuccess(decodedText) {
    if (!isScanning) return;
    isScanning = false;

    var overlay = document.getElementById('scan-success-overlay');
    if (overlay) overlay.style.display = 'flex';

    if (html5QrCode) {
        html5QrCode.stop().catch(function(e){ console.warn(e); }).finally(function() {
            var token = extractToken(decodedText);
            if (token) {
                window.location.href = "{{ url('/admin/qr/verify') }}/" + encodeURIComponent(token);
            } else {
                alert('QR Code tidak berisi token yang valid.');
                if (overlay) overlay.style.display = 'none';
                startCamera();
            }
        });
    } else {
        var token = extractToken(decodedText);
        window.location.href = "{{ url('/admin/qr/verify') }}/" + encodeURIComponent(token);
    }
}

function startCamera() {
    hideCameraError();
    var placeholder = document.getElementById('camera-placeholder');
    var controls = document.getElementById('camera-controls');

    if (!html5QrCode) {
        html5QrCode = new Html5Qrcode("qr-reader");
    }

    var config = {
        fps: 10,
        qrbox: function(viewfinderWidth, viewfinderHeight) {
            var edge = Math.min(viewfinderWidth, viewfinderHeight);
            return {
                width: Math.floor(edge * 0.75),
                height: Math.floor(edge * 0.75)
            };
        },
        aspectRatio: 1.0
    };

    html5QrCode.start(
        { facingMode: currentFacingMode },
        config,
        onScanSuccess,
        function(errorMessage) {
            // Scanning in progress...
        }
    ).then(function() {
        isScanning = true;
        if (placeholder) placeholder.style.display = 'none';
        if (controls) controls.style.display = 'flex';

        Html5Qrcode.getCameras().then(function(cameras) {
            var btnSwitch = document.getElementById('btn-switch-camera');
            if (btnSwitch && cameras && cameras.length > 1) {
                btnSwitch.style.display = 'inline-flex';
            }
        }).catch(function(){});
    }).catch(function(err) {
        isScanning = false;
        if (placeholder) placeholder.style.display = 'flex';
        if (controls) controls.style.display = 'none';

        var errString = String(err).toLowerCase();
        if (errString.includes('notallowed') || errString.includes('permission')) {
            showCameraError('Izin akses kamera ditolak. Silakan izinkan akses kamera di pengaturan browser Anda, atau gunakan input manual di bawah.');
        } else if (errString.includes('notfound') || errString.includes('devicesnotfound')) {
            showCameraError('Tidak ditemukan perangkat kamera pada device ini. Silakan gunakan input manual kode QR.');
        } else if (errString.includes('notreadable') || errString.includes('trackstart')) {
            showCameraError('Kamera sedang digunakan oleh aplikasi lain atau tidak dapat diakses.');
        } else {
            showCameraError('Gagal mengakses kamera: ' + (err.message || err));
        }
    });
}

function stopCamera() {
    if (html5QrCode && isScanning) {
        html5QrCode.stop().then(function() {
            isScanning = false;
            var placeholder = document.getElementById('camera-placeholder');
            var controls = document.getElementById('camera-controls');
            if (placeholder) placeholder.style.display = 'flex';
            if (controls) controls.style.display = 'none';
        }).catch(function(err) {
            console.warn(err);
        });
    }
}

function switchCamera() {
    if (!isScanning) return;
    currentFacingMode = (currentFacingMode === "environment") ? "user" : "environment";
    stopCamera();
    setTimeout(startCamera, 300);
}

function triggerFileInput() {
    var fileInput = document.getElementById('qr-file-input');
    if (fileInput) fileInput.click();
}

function handleFileUpload(event) {
    var file = event.target.files && event.target.files[0];
    if (!file) return;
    scanImageFile(file);
    // Reset file input agar file yang sama bisa dipilih ulang bila diperlukan
    event.target.value = '';
}

function handleDecodedToken(decodedText) {
    var overlay = document.getElementById('scan-success-overlay');
    var overlayTitle = document.getElementById('scan-success-title');
    var overlaySub = document.getElementById('scan-success-sub');

    if (overlayTitle) overlayTitle.textContent = 'QR Berhasil Terbaca!';
    if (overlaySub) overlaySub.textContent = 'Mengalihkan ke halaman verifikasi...';
    if (overlay) overlay.style.display = 'flex';

    var token = extractToken(decodedText);
    if (token) {
        setTimeout(function() {
            window.location.href = "{{ url('/admin/qr/verify') }}/" + encodeURIComponent(token);
        }, 350);
    } else {
        if (overlay) overlay.style.display = 'none';
        showCameraError('QR Code tidak berisi token yang valid.');
    }
}

function scanImageFile(file) {
    if (!file || !file.type.startsWith('image/')) {
        showCameraError('File yang dipilih harus berupa gambar (JPG, PNG, WEBP, dll).');
        return;
    }

    hideCameraError();
    var overlay = document.getElementById('scan-success-overlay');
    var overlayTitle = document.getElementById('scan-success-title');
    var overlaySub = document.getElementById('scan-success-sub');
    var placeholder = document.getElementById('camera-placeholder');

    if (overlayTitle) overlayTitle.textContent = 'Menganalisis Gambar...';
    if (overlaySub) overlaySub.textContent = 'Mencari kode QR pada foto yang diunggah...';
    if (overlay) overlay.style.display = 'flex';

    if (isScanning && html5QrCode) {
        html5QrCode.stop().then(function() {
            isScanning = false;
            var controls = document.getElementById('camera-controls');
            if (controls) controls.style.display = 'none';
            runMultiPassScan(file);
        }).catch(function() {
            runMultiPassScan(file);
        });
    } else {
        runMultiPassScan(file);
    }
}

function runMultiPassScan(file) {
    var overlay = document.getElementById('scan-success-overlay');
    var placeholder = document.getElementById('camera-placeholder');

    decodeWithJsQR(file)
        .then(function(resultText) {
            handleDecodedToken(resultText);
        })
        .catch(function() {
            // Fallback to html5QrCode engine
            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("qr-reader");
            }

            html5QrCode.scanFile(file, false)
                .then(function(decodedText) {
                    handleDecodedToken(decodedText);
                })
                .catch(function() {
                    if (overlay) overlay.style.display = 'none';
                    if (placeholder) placeholder.style.display = 'flex';
                    showCameraError('Tidak dapat mendeteksi QR Code pada foto tersebut. Pastikan gambar jelas, fokus, dan tidak terpotong.');
                });
        });
}

function decodeWithJsQR(file) {
    return new Promise(function(resolve, reject) {
        if (typeof jsQR === 'undefined') {
            reject(new Error('jsQR library not loaded'));
            return;
        }

        var reader = new FileReader();
        reader.onload = function(e) {
            var img = new Image();
            img.onload = function() {
                var maxSizes = [
                    { w: img.width, h: img.height },
                    { w: 1200, h: Math.round(img.height * (1200 / img.width)) },
                    { w: 800,  h: Math.round(img.height * (800 / img.width)) },
                    { w: 500,  h: Math.round(img.height * (500 / img.width)) }
                ];

                var canvas = document.createElement('canvas');
                var ctx = canvas.getContext('2d', { willReadFrequently: true });

                for (var i = 0; i < maxSizes.length; i++) {
                    var targetW = maxSizes[i].w;
                    var targetH = maxSizes[i].h;
                    if (targetW <= 0 || targetH <= 0) continue;
                    if (i > 0 && targetW >= img.width) continue;

                    canvas.width = targetW;
                    canvas.height = targetH;
                    ctx.drawImage(img, 0, 0, targetW, targetH);

                    var imageData = ctx.getImageData(0, 0, targetW, targetH);
                    var code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: "dontInvert"
                    });

                    if (!code) {
                        code = jsQR(imageData.data, imageData.width, imageData.height, {
                            inversionAttempts: "attemptBoth"
                        });
                    }

                    if (code && code.data && code.data.trim().length > 0) {
                        resolve(code.data);
                        return;
                    }
                }

                reject(new Error('QR code not detected in image'));
            };
            img.onerror = function() { reject(new Error('Failed to load image')); };
            img.src = e.target.result;
        };
        reader.onerror = function() { reject(new Error('Failed to read file')); };
        reader.readAsDataURL(file);
    });
}

// Drag and drop handler pada viewport
(function setupDragAndDrop() {
    var dropZone = document.querySelector('.qr-viewport-wrap');
    if (!dropZone) return;

    ['dragenter', 'dragover'].forEach(function(eventName) {
        dropZone.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.style.borderColor = 'var(--blue)';
            dropZone.style.borderStyle = 'dashed';
        });
    });

    ['dragleave', 'drop'].forEach(function(eventName) {
        dropZone.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.style.borderColor = 'var(--border-alt)';
            dropZone.style.borderStyle = 'solid';
        });
    });

    dropZone.addEventListener('drop', function(e) {
        var dt = e.dataTransfer;
        if (dt && dt.files && dt.files.length > 0) {
            scanImageFile(dt.files[0]);
        }
    });
})();

function handleManualSubmit(e) {
    e.preventDefault();
    var input = document.getElementById('manualTokenInput');
    if (!input || !input.value.trim()) {
        alert('Mohon masukkan kode token atau tempel URL QR.');
        return;
    }
    var token = extractToken(input.value);
    if (!token) {
        alert('Token tidak valid.');
        return;
    }
    stopCamera();
    window.location.href = "{{ url('/admin/qr/verify') }}/" + encodeURIComponent(token);
}

window.addEventListener('beforeunload', function() {
    if (html5QrCode && isScanning) {
        html5QrCode.stop().catch(function(){});
    }
});
</script>
@endsection
