@extends('layouts.siswa')

@section('title', 'Profil Siswa')
@section('page-heading', 'Profil Siswa')

@section('content')
<style>
    .profile-header {
        background: var(--card); border: 1px solid var(--border2);
        border-radius: 14px; padding: 24px; margin-bottom: 20px;
    }
    .profile-header-content { display: flex; align-items: center; gap: 20px; }
    .profile-avatar {
        width: 80px; height: 80px; border-radius: 50%;
        background: var(--primary-dark);
        display: flex; align-items: center; justify-content: center;
        font-size: 28px; font-weight: 700; color: #fff; flex-shrink: 0;
    }
    .profile-info h1 { font-size: 22px; font-weight: 700; color: var(--text); margin-bottom: 4px; }
    .profile-info p { font-size: 14px; color: var(--muted); }
    .profile-role {
        display: inline-block; padding: 4px 12px; background: var(--bg3);
        border: 1px solid var(--border2); border-radius: 6px;
        font-size: 12px; font-weight: 600; color: var(--text); margin-top: 8px;
    }

    .profile-section {
        background: var(--card); border: 1px solid var(--border2);
        border-radius: 14px; padding: 24px; margin-bottom: 20px;
    }
    .section-title { font-size: 16px; font-weight: 700; color: var(--text); margin-bottom: 16px; }
    
    .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
    @media (max-width: 640px) { .info-grid { grid-template-columns: 1fr; } }
    
    .info-item { padding: 16px; background: var(--bg3); border: 1px solid var(--border2); border-radius: 10px; }
    .info-label { font-size: 12px; color: var(--muted); font-weight: 600; margin-bottom: 4px; }
    .info-value { font-size: 14px; font-weight: 600; color: var(--text); }

    .stat-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    @media (max-width: 640px) { .stat-grid { grid-template-columns: 1fr; } }
    
    .stat-card {
        padding: 20px; background: var(--bg3); border: 1px solid var(--border2);
        border-radius: 10px; text-align: center;
    }
    .stat-value { font-size: 28px; font-weight: 700; color: var(--primary); }
    .stat-label { font-size: 12px; color: var(--muted); font-weight: 600; margin-top: 4px; }

    .btn {
        padding: 10px 20px; background: var(--primary); color: #fff;
        border: none; border-radius: 8px; font-size: 13px; font-weight: 600;
        cursor: pointer; text-decoration: none; display: inline-block;
    }
    .btn:hover { background: var(--primary-dark); }
    .btn-outline {
        background: transparent; color: var(--text);
        border: 1px solid var(--border2);
    }
    .btn-outline:hover { background: var(--bg3); }
</style>

<div>
    {{-- Profile Header --}}
    <div class="profile-header">
        <div class="profile-header-content">
            <div class="profile-avatar">
                {{ auth()->check() ? strtoupper(substr(auth()->user()->name,0,2)) : 'SI' }}
            </div>
            <div class="profile-info">
                <h1>{{ auth()->check() ? auth()->user()->name : 'Siswa' }}</h1>
                <p>{{ auth()->check() ? auth()->user()->email : '-' }}</p>
                <div class="profile-role">Peminjam Barang</div>
            </div>
        </div>
    </div>

    {{-- Statistics --}}
    <div class="profile-section">
        <div class="section-title">Statistik Peminjaman</div>
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-value">
                    {{ \App\Models\BorrowingRequest::where('user_id', auth()->id())->count() }}
                </div>
                <div class="stat-label">Total Peminjaman</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">
                    {{ \App\Models\BorrowingRequest::where('user_id', auth()->id())->where('status', 'borrowed')->count() }}
                </div>
                <div class="stat-label">Sedang Dipinjam</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">
                    {{ \App\Models\BorrowingRequest::where('user_id', auth()->id())->where('status', 'returned')->count() }}
                </div>
                <div class="stat-label">Sudah Dikembalikan</div>
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
                <div class="info-value">Peminjam (Siswa)</div>
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
            <a href="{{ route('student.catalog') }}" class="btn">Lihat Katalog Barang</a>
            <a href="{{ route('student.history') }}" class="btn btn-outline">Lihat Riwayat Peminjaman</a>
            <a href="{{ route('student.announcements') }}" class="btn btn-outline">Lihat Pengumuman</a>
        </div>
    </div>
</div>
@endsection
