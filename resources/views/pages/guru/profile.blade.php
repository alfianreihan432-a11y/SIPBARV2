@extends('layouts.guru')

@section('title', 'Profil Guru')
@section('page-heading', 'Profil Guru')

@section('content')
<style>
    .page-header {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 14px;
        padding: 24px;
        margin-bottom: 20px;
    }
    .profile-header-content {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }
    .profile-info h1 {
        font-size: 22px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
    }
    .profile-info p {
        font-size: 14px;
        color: var(--muted);
    }
    .profile-role {
        display: inline-block;
        padding: 4px 12px;
        background: var(--bg3);
        border: 1px solid var(--border2);
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        color: var(--text);
        margin-top: 8px;
    }

    .profile-section {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 14px;
        padding: 24px;
        margin-bottom: 20px;
    }
    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 16px;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    @media (max-width: 640px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .info-item {
        padding: 16px;
        background: var(--bg3);
        border: 1px solid var(--border2);
        border-radius: 10px;
    }
    .info-label {
        font-size: 12px;
        color: var(--muted);
        font-weight: 600;
        margin-bottom: 4px;
    }
    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
    }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    @media (max-width: 640px) {
        .stat-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 480px) {
        .stat-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .stat-card {
        padding: 20px;
        background: var(--bg3);
        border: 1px solid var(--border2);
        border-radius: 10px;
        text-align: center;
    }
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--accent);
    }
    .stat-label {
        font-size: 12px;
        color: var(--muted);
        font-weight: 600;
        margin-top: 4px;
    }

    .btn {
        padding: 10px 20px;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    .btn:hover {
        opacity: 0.9;
    }
    .btn-outline {
        background: transparent;
        color: var(--text);
        border: 1px solid var(--border2);
    }
    .btn-outline:hover {
        background: var(--bg3);
    }
</style>

<div>
    {{-- Profile Header --}}
    <div class="page-header">
        <div class="profile-header-content">
            <div style="position:relative;flex-shrink:0">
                @if(auth()->check() && auth()->user()->hasProfilePhoto())
                    <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="profile-avatar" style="object-fit:cover">
                @else
                    <div class="profile-avatar">
                        {{ auth()->check() ? strtoupper(substr(auth()->user()->name,0,2)) : 'GU' }}
                    </div>
                @endif
                <label for="foto_profil" style="position:absolute;bottom:0;right:0;width:32px;height:32px;background:var(--accent);border:2px solid #fff;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,.15);transition:all .2s" onmouseover="this.style.background='#0f766e'" onmouseout="this.style.background='var(--accent)'">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </label>
                <input type="file" id="foto_profil" name="foto_profil" accept="image/jpeg,image/jpg,image/png" style="display:none" onchange="previewAndUpload(this)">
            </div>
            <div class="profile-info">
                <h1>{{ auth()->check() ? auth()->user()->name : 'Guru' }}</h1>
                <p>{{ auth()->check() ? auth()->user()->email : '-' }}</p>
                <div class="profile-role">Guru Pembimbing</div>
            </div>
        </div>
    </div>

    {{-- Statistics --}}
    <div class="profile-section">
        <div class="section-title">Statistik Persetujuan</div>
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-value">
                    {{ \App\Models\BorrowingRequest::where('teacher_id', auth()->id())->count() }}
                </div>
                <div class="stat-label">Total Permohonan</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">
                    {{ \App\Models\BorrowingRequest::where('teacher_id', auth()->id())->where('status', 'pending')->count() }}
                </div>
                <div class="stat-label">Menunggu Persetujuan</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">
                    {{ \App\Models\BorrowingRequest::where('teacher_id', auth()->id())->where('status', 'approved')->count() }}
                </div>
                <div class="stat-label">Disetujui</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">
                    {{ \App\Models\BorrowingRequest::where('teacher_id', auth()->id())->where('status', 'rejected')->count() }}
                </div>
                <div class="stat-label">Ditolak</div>
            </div>
        </div>
    </div>

    {{-- Personal Information --}}
    <div class="profile-section">
        <div class="section-title">Informasi Pribadi</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Nama Lengkap</div>
                <div class="info-value">{{ auth()->check() ? auth()->user()->name : '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value">{{ auth()->check() ? auth()->user()->email : '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Role</div>
                <div class="info-value">Guru Pembimbing</div>
            </div>
            <div class="info-item">
                <div class="info-label">Terdaftar Sejak</div>
                <div class="info-value">
                    {{ auth()->check() ? \Carbon\Carbon::parse(auth()->user()->created_at)->format('d M Y') : '-' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="profile-section">
        <div class="section-title">Aksi Cepat</div>
        <div style="display:flex;gap:12px;flex-wrap:wrap">
            <a href="{{ route('teacher.requests') }}" class="btn">Lihat Permohonan</a>
            <a href="{{ route('teacher.loans') }}" class="btn btn-outline">Lihat Peminjaman Aktif</a>
            <a href="{{ route('teacher.returns') }}" class="btn btn-outline">Lihat Pengembalian</a>
            <a href="{{ route('teacher.reports') }}" class="btn btn-outline">Lihat Laporan</a>
        </div>
    </div>
</div>

<script>
function previewAndUpload(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB');
            input.value = '';
            return;
        }
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            alert('Format file harus JPG, JPEG, atau PNG');
            input.value = '';
            return;
        }
        
        // Get CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            alert('CSRF token tidak ditemukan. Silakan refresh halaman.');
            return;
        }
        
        // Create FormData
        const formData = new FormData();
        formData.append('foto_profil', file);
        formData.append('_token', csrfToken);
        
        // Upload via AJAX
        fetch('{{ route("teacher.profile.photo.update") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (response.status === 419) {
                throw new Error('CSRF token expired. Silakan refresh halaman.');
            }
            return response.json().catch(e => {
                console.error('JSON parse error:', e);
                return { success: false, message: 'Invalid response from server' };
            });
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Reload page to show new photo
                window.location.reload();
            } else {
                alert(data.message || 'Gagal mengupload foto');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'Terjadi kesalahan saat mengupload foto');
        });
    }
}
</script>
@endsection
