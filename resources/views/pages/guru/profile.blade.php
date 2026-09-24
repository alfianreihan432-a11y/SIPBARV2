@extends('layouts.guru')

@section('title', 'Profil Guru – SIPBAR')
@section('page-heading', 'Profil Guru')

@section('content')
@php
    $user = auth()->user();
    $totalRequests = \App\Models\BorrowingRequest::where('teacher_id', auth()->id())
        ->where('tipe_peminjam', 'siswa')
        ->count();
    $pendingRequests = \App\Models\BorrowingRequest::where('teacher_id', auth()->id())
        ->where('tipe_peminjam', 'siswa')
        ->where('status', \App\Models\BorrowingRequest::STATUS_PENDING)->count();
    $activeRequests = \App\Models\BorrowingRequest::where('teacher_id', auth()->id())
        ->where('tipe_peminjam', 'siswa')
        ->whereIn('status', [\App\Models\BorrowingRequest::STATUS_APPROVED, 'qr_ready', \App\Models\BorrowingRequest::STATUS_BORROWED])->count();
    $returnedRequests = \App\Models\BorrowingRequest::where('teacher_id', auth()->id())
        ->where('tipe_peminjam', 'siswa')
        ->where('status', \App\Models\BorrowingRequest::STATUS_RETURNED)->count();
@endphp

<style>
    .profile-wrapper { max-width: 860px; margin: 0 auto; }

    .profile-hero {
        background: var(--card); border: 1px solid var(--border2);
        border-radius: 16px; padding: 28px; margin-bottom: 20px;
        position: relative; overflow: hidden;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.05), 0 1px 3px rgba(0,0,0,0.03);
    }
    .profile-hero::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0;
        height: 3px; background: var(--accent); border-radius: 16px 16px 0 0;
    }
    .profile-hero-inner { display: flex; align-items: center; gap: 22px; flex-wrap: wrap; }
    .avatar-wrap { position: relative; flex-shrink: 0; }
    .profile-avatar {
        width: 88px; height: 88px; border-radius: 50%;
        background: var(--accent); display: flex; align-items: center; justify-content: center;
        font-family: inherit; font-size: 30px; font-weight: 800; color: #fff;
        border: 2px solid var(--border2); overflow: hidden;
    }
    .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .profile-meta h1 { font-size: 22px; font-weight: 800; color: var(--text); margin-bottom: 3px; }
    .profile-meta .email { font-size: 13.5px; color: var(--muted); margin-bottom: 10px; font-weight: 500; }
    .role-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 14px; background: rgba(16, 185, 129, 0.12);
        border: 1px solid rgba(16, 185, 129, 0.25); border-radius: 999px;
        font-size: 12px; font-weight: 700; color: var(--accent);
    }
    .jurusan-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 14px; background: var(--bg3); border: 1px solid var(--border2);
        border-radius: 999px; font-size: 12px; font-weight: 700; color: var(--text); margin-left: 8px;
    }

    .stat-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px; }
    @media (max-width: 680px) { .stat-row { grid-template-columns: repeat(2, 1fr); } }
    .stat-box {
        background: var(--card); border: 1px solid var(--border2);
        border-radius: 12px; padding: 18px 16px; text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }
    .stat-num { font-size: 28px; font-weight: 800; color: var(--text); line-height: 1; }
    .stat-lbl { font-size: 11.5px; font-weight: 600; color: var(--muted); margin-top: 6px; }

    .info-card {
        background: var(--card); border: 1px solid var(--border2);
        border-radius: 14px; padding: 22px 24px; margin-bottom: 18px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }
    .info-card-title {
        font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 16px;
        padding-bottom: 12px; border-bottom: 1px solid var(--border2);
        display: flex; align-items: center; gap: 8px;
    }
    .info-card-title::before {
        content: '';
        width: 3.5px;
        height: 16px;
        background: var(--accent);
        border-radius: 2px;
        display: inline-block;
    }
    .info-card-title svg { width: 16px; height: 16px; color: var(--accent); }
    .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
    @media (max-width: 560px) { .info-grid { grid-template-columns: 1fr; } }
    .info-row {
        background: var(--bg3); border: 1px solid var(--border2);
        border-radius: 10px; padding: 14px 16px;
    }
    .info-lbl {
        font-size: 11px; font-weight: 800; color: var(--accent);
        text-transform: uppercase; letter-spacing: .05em; margin-bottom: 4px;
    }
    .info-val { font-size: 14px; font-weight: 700; color: var(--text); }

    .action-row { display: flex; gap: 10px; flex-wrap: wrap; }
    .btn-action {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 18px; border-radius: 9px; font-size: 13px;
        font-weight: 600; cursor: pointer; text-decoration: none;
        transition: all .18s; border: none;
    }
    .btn-primary { background: var(--accent); color: #fff; }
    .btn-primary:hover { background: var(--accent-hover); color: #fff; }
    .btn-outline { background: var(--bg3); color: var(--text); border: 1px solid var(--border2); }
    .btn-outline:hover { background: var(--border2); color: var(--text); }

    .flash { padding: 12px 16px; border-radius: 10px; margin-bottom: 18px; font-size: 13.5px; font-weight: 500; }
    .flash-success { background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.25); color: #059669; }
    .flash-error   { background: #fee2e2; border: 1px solid #fca5a5; color: #b91c1c; }
</style>

<div class="profile-wrapper">

    @if(session('success'))
        <div class="flash flash-success" style="display:flex;align-items:center;gap:8px">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flash flash-error" style="display:flex;align-items:center;gap:8px">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="flash flash-error">
            @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
        </div>
    @endif

    {{-- Hero Card --}}
    <div class="profile-hero">
        <div class="profile-hero-inner">
            <div class="avatar-wrap">
                @if($user && $user->hasProfilePhoto())
                    <div class="profile-avatar">
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                    </div>
                @else
                    <div class="profile-avatar">{{ $user ? strtoupper(substr($user->name, 0, 2)) : 'GU' }}</div>
                @endif
            </div>

            <div class="profile-meta">
                <h1>{{ $user ? $user->name : 'Guru' }}</h1>
                <p class="email">{{ $user ? $user->email : '-' }}</p>
                <span class="role-badge">Guru Penanggung Jawab</span>
                @if($user && $user->jurusan)
                    <span class="jurusan-badge">{{ $user->jurusan->nama }}</span>
                @elseif($user && $user->nip)
                    <span class="jurusan-badge">NIP: {{ $user->nip }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- 4 Stat Cards --}}
    <div class="stat-row">
        <div class="stat-box">
            <div class="stat-num">{{ $totalRequests }}</div>
            <div class="stat-lbl">Total Permohonan</div>
        </div>
        <div class="stat-box">
            <div class="stat-num">{{ $pendingRequests }}</div>
            <div class="stat-lbl">Menunggu Persetujuan</div>
        </div>
        <div class="stat-box">
            <div class="stat-num">{{ $activeRequests }}</div>
            <div class="stat-lbl">Sedang Dipinjam</div>
        </div>
        <div class="stat-box">
            <div class="stat-num">{{ $returnedRequests }}</div>
            <div class="stat-lbl">Selesai / Dikembalikan</div>
        </div>
    </div>

    {{-- Informasi Akun --}}
    <div class="info-card">
        <div class="info-card-title">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Informasi Akun
        </div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-lbl">Nama Lengkap</div>
                <div class="info-val">{{ $user ? $user->name : '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-lbl">Alamat Email</div>
                <div class="info-val">{{ $user ? $user->email : '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-lbl">Jurusan / Unit</div>
                <div class="info-val">{{ $user ? ($user->jurusan?->nama ?? ($user->nip ? 'NIP: '.$user->nip : 'Guru Penanggung Jawab')) : '—' }}</div>
            </div>
            <div class="info-row">
                <div class="info-lbl">Peran</div>
                <div class="info-val">Guru Penanggung Jawab</div>
            </div>
            <div class="info-row">
                <div class="info-lbl">Terdaftar Sejak</div>
                <div class="info-val">{{ $user && $user->created_at ? \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y') : '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-lbl">Status Akun</div>
                <div class="info-val" style="color:#10b981;display:flex;align-items:center;gap:6px">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    Aktif
                </div>
            </div>
            <div class="info-row" style="position:relative">
                <div class="info-lbl">Nomor WhatsApp</div>
                <div class="info-val" id="phoneDisplay">{{ $user && $user->phone ? $user->phone : 'Belum diisi' }}</div>
                <button onclick="togglePhoneEdit()" style="position:absolute;top:14px;right:14px;background:var(--accent);color:#fff;border:none;border-radius:6px;padding:4px 10px;font-size:10px;font-weight:700;cursor:pointer;transition:all .2s" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                    Edit
                </button>
                <div id="phoneEditForm" style="display:none;margin-top:10px">
                    <form onsubmit="updatePhone(event)">
                        <div style="display:flex;gap:6px;align-items:flex-start">
                            <input type="tel" id="phoneInput" name="phone" value="{{ $user ? $user->phone : '' }}" placeholder="08xx-xxxx-xxxx" style="flex:1;padding:8px 10px;border:1px solid var(--border2);border-radius:7px;font-size:13px;background:var(--card);color:var(--text);outline:none" pattern="[0-9]{10,13}" title="10-13 digit">
                            <button type="submit" style="padding:8px 12px;background:var(--accent);color:#fff;border:none;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer">Simpan</button>
                            <button type="button" onclick="togglePhoneEdit()" style="padding:8px 12px;background:var(--bg3);color:var(--text);border:1px solid var(--border2);border-radius:7px;font-size:12px;font-weight:600;cursor:pointer">Batal</button>
                        </div>
                        <small style="display:block;margin-top:4px;font-size:10px;color:var(--muted)">Format: 08xxxxxxxxxx</small>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="info-card">
        <div class="info-card-title">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Aksi Cepat
        </div>
        <div class="action-row">
            <a href="{{ route('teacher.dashboard') }}" class="btn-action btn-primary">
                Dashboard
            </a>
            <a href="{{ route('teacher.requests') }}" class="btn-action btn-outline">
                Permohonan Siswa
            </a>
            <a href="{{ route('teacher.peminjaman-guru') }}" class="btn-action btn-outline">
                Peminjaman Guru
            </a>
            <a href="{{ route('teacher.pengembalian-guru') }}" class="btn-action btn-outline">
                Riwayat Pengembalian
            </a>
        </div>
    </div>

</div>

<script>
function togglePhoneEdit() {
    const display = document.getElementById('phoneDisplay');
    const form = document.getElementById('phoneEditForm');
    if (form.style.display === 'none') {
        display.style.display = 'none';
        form.style.display = 'block';
        document.getElementById('phoneInput').focus();
    } else {
        display.style.display = 'block';
        form.style.display = 'none';
    }
}

function updatePhone(event) {
    event.preventDefault();
    const phoneInput = document.getElementById('phoneInput');
    const phone = phoneInput.value.trim();
    if (phone && !/^[0-9]{10,13}$/.test(phone)) {
        alert('Nomor telepon harus 10-13 digit angka');
        return;
    }
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrf) { alert('CSRF token tidak ditemukan. Refresh halaman.'); return; }
    const fd = new FormData();
    fd.append('phone', phone);
    fd.append('_token', csrf);
    const btn = event.target.querySelector('[type="submit"]');
    const orig = btn.textContent;
    btn.textContent = 'Menyimpan...';
    btn.disabled = true;
    fetch('{{ route("teacher.profile.phone.update") }}', {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => {
        if (r.status === 419) throw new Error('CSRF expired. Refresh halaman.');
        return r.json();
    })
    .then(data => {
        if (data.success) {
            document.getElementById('phoneDisplay').textContent = phone || 'Belum diisi';
            togglePhoneEdit();
            alert('Nomor WhatsApp berhasil diperbarui!');
        } else {
            alert(data.message || 'Gagal memperbarui');
        }
    })
    .catch(err => alert(err.message || 'Terjadi kesalahan'))
    .finally(() => {
        btn.textContent = orig;
        btn.disabled = false;
    });
}
</script>
@endsection
