@extends('layouts.admin')
@section('title', 'Pengaturan - Keamanan')
@section('page-heading', 'Pengaturan')

@section('content')
<div style="display:flex;flex-direction:column;gap:20px">

    {{-- ═══ HERO HEADER ═══ --}}
    <div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;padding:22px 28px;display:flex;align-items:center;gap:18px;box-shadow:var(--card-shadow)">
        <div style="width:48px;height:48px;background:var(--blue-dark);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <div style="font-size:11px;font-weight:700;color:var(--blue);letter-spacing:.1em;text-transform:uppercase;margin-bottom:4px">Akun & Preferensi</div>
            <div style="font-size:19px;font-weight:800;color:var(--text-primary);margin-bottom:4px">Pengaturan</div>
            <div style="font-size:13px;color:var(--text-muted)">Kelola profil, keamanan, dan tampilan akun Anda.</div>
        </div>
    </div>

    {{-- ═══ SETTINGS TABS ═══ --}}
    @include('partials.settings-tabs', ['active' => 'security'])

    {{-- ═══ CHANGE PASSWORD ═══ --}}
    <div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;box-shadow:var(--card-shadow);overflow:hidden">
        <div style="padding:22px 28px;border-bottom:1px solid var(--border-subtle)">
            <div style="font-size:16px;font-weight:800;color:var(--text-primary);margin-bottom:3px">Ubah Kata Sandi</div>
            <div style="font-size:13px;color:var(--text-muted)">Pastikan kata sandi Anda kuat dan berbeda dari yang sebelumnya.</div>
        </div>
        <form method="POST" action="{{ route('settings.password.update') }}" style="padding:24px 28px;max-width:520px">
            @csrf
            @method('PUT')

            @if(session('status') === 'password-updated')
            <div style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:11px;background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);color:#10b981;font-size:13px;font-weight:600;margin-bottom:20px">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Kata sandi berhasil diperbarui.
            </div>
            @endif

            <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:16px">
                <label for="current_password" style="font-size:11px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--text-muted)">Kata Sandi Saat Ini</label>
                <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                    style="width:100%;background:var(--input-bg);border:1.5px solid {{ $errors->has('current_password') ? '#f87171' : 'var(--input-border)' }};border-radius:10px;padding:10px 14px;font-size:14px;color:var(--text-primary);outline:none;font-family:inherit">
                @error('current_password') <p style="font-size:12px;color:#f87171;margin-top:3px">{{ $message }}</p> @enderror
            </div>

            <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:16px">
                <label for="password" style="font-size:11px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--text-muted)">Kata Sandi Baru</label>
                <input id="password" name="password" type="password" autocomplete="new-password"
                    style="width:100%;background:var(--input-bg);border:1.5px solid {{ $errors->has('password') ? '#f87171' : 'var(--input-border)' }};border-radius:10px;padding:10px 14px;font-size:14px;color:var(--text-primary);outline:none;font-family:inherit">
                @error('password') <p style="font-size:12px;color:#f87171;margin-top:3px">{{ $message }}</p> @enderror
            </div>

            <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:24px">
                <label for="password_confirmation" style="font-size:11px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--text-muted)">Konfirmasi Kata Sandi Baru</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                    style="width:100%;background:var(--input-bg);border:1.5px solid var(--input-border);border-radius:10px;padding:10px 14px;font-size:14px;color:var(--text-primary);outline:none;font-family:inherit">
            </div>

            <div style="padding-top:18px;border-top:1px solid var(--border-subtle)">
                <button type="submit" style="display:inline-flex;align-items:center;gap:7px;background:var(--blue-dark);color:#fff;border:none;border-radius:10px;padding:10px 22px;font-size:13px;font-weight:700;cursor:pointer;box-shadow:0 4px 12px rgba(29,78,216,.3);transition:all .2s" onmouseover="this.style.opacity='.9'" onmouseout="this.style.opacity='1'">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Perbarui Kata Sandi
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
