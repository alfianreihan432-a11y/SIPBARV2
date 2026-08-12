@extends('layouts.admin')
@section('title', 'Pengaturan - Tampilan')
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
    @include('partials.settings-tabs', ['active' => 'appearance'])

    {{-- ═══ APPEARANCE CARD ═══ --}}
    <div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;box-shadow:var(--card-shadow);overflow:hidden">
        <div style="padding:22px 28px;border-bottom:1px solid var(--border-subtle)">
            <div style="font-size:16px;font-weight:800;color:var(--text-primary);margin-bottom:3px">Mode Tampilan</div>
            <div style="font-size:13px;color:var(--text-muted)">Pilih tema tampilan yang nyaman untuk Anda. Preferensi tersimpan otomatis di browser.</div>
        </div>
        <div style="padding:24px 28px">
            <div style="display:flex;gap:14px;flex-wrap:wrap">

                {{-- Dark Mode Card --}}
                <div id="card-dark" onclick="setSipbarTheme('dark')" style="cursor:pointer;border-radius:16px;border:2px solid var(--border-alt);overflow:hidden;transition:all .2s;flex:1;min-width:180px;max-width:240px" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="background:#0f172a;padding:16px;border-bottom:1px solid #1e293b">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px">
                            <div style="width:8px;height:8px;border-radius:50%;background:#ef4444"></div>
                            <div style="width:8px;height:8px;border-radius:50%;background:#f59e0b"></div>
                            <div style="width:8px;height:8px;border-radius:50%;background:#10b981"></div>
                        </div>
                        <div style="background:#1e293b;border-radius:8px;padding:8px 10px;margin-bottom:6px">
                            <div style="width:60%;height:6px;background:#334155;border-radius:3px;margin-bottom:5px"></div>
                            <div style="width:40%;height:5px;background:#1e40af;border-radius:3px"></div>
                        </div>
                        <div style="background:#1e293b;border-radius:8px;padding:8px 10px">
                            <div style="width:80%;height:5px;background:#334155;border-radius:3px;margin-bottom:4px"></div>
                            <div style="width:50%;height:5px;background:#334155;border-radius:3px"></div>
                        </div>
                    </div>
                    <div style="padding:14px 16px;display:flex;align-items:center;justify-content:space-between;background:var(--bg-card)">
                        <div>
                            <div style="font-size:13px;font-weight:700;color:var(--text-primary)">🌙 Mode Gelap</div>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:2px">Cocok untuk malam hari</div>
                        </div>
                        <div id="check-dark" style="width:20px;height:20px;border-radius:50%;background:var(--blue-dark);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Light Mode Card --}}
                <div id="card-light" onclick="setSipbarTheme('light')" style="cursor:pointer;border-radius:16px;border:2px solid var(--border-alt);overflow:hidden;transition:all .2s;flex:1;min-width:180px;max-width:240px" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="background:#f8fafc;padding:16px;border-bottom:1px solid #e2e8f0">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px">
                            <div style="width:8px;height:8px;border-radius:50%;background:#ef4444"></div>
                            <div style="width:8px;height:8px;border-radius:50%;background:#f59e0b"></div>
                            <div style="width:8px;height:8px;border-radius:50%;background:#10b981"></div>
                        </div>
                        <div style="background:#fff;border-radius:8px;padding:8px 10px;margin-bottom:6px;border:1px solid #e2e8f0">
                            <div style="width:60%;height:6px;background:#e2e8f0;border-radius:3px;margin-bottom:5px"></div>
                            <div style="width:40%;height:5px;background:#1d4ed8;border-radius:3px"></div>
                        </div>
                        <div style="background:#fff;border-radius:8px;padding:8px 10px;border:1px solid #e2e8f0">
                            <div style="width:80%;height:5px;background:#e2e8f0;border-radius:3px;margin-bottom:4px"></div>
                            <div style="width:50%;height:5px;background:#e2e8f0;border-radius:3px"></div>
                        </div>
                    </div>
                    <div style="padding:14px 16px;display:flex;align-items:center;justify-content:space-between;background:var(--bg-card)">
                        <div>
                            <div style="font-size:13px;font-weight:700;color:var(--text-primary)">☀️ Mode Terang</div>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:2px">Cocok untuk siang hari</div>
                        </div>
                        <div id="check-light" style="width:20px;height:20px;border-radius:50%;background:var(--border-alt);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px;color:var(--text-muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                </div>

            </div>

            <p style="margin-top:16px;font-size:12px;color:var(--text-subtle)">💡 Tip: Anda juga bisa tekan <kbd style="background:var(--bg-card-subtle);border:1px solid var(--border-alt);border-radius:5px;padding:2px 6px;font-size:11px;color:var(--text-primary);font-family:monospace">Alt + D</kbd> di mana saja untuk beralih tema dengan cepat.</p>
        </div>
    </div>

</div>

<script>
function setSipbarTheme(theme) {
    var html = document.documentElement;
    var KEY = 'sipbar-dash-theme';
    var isLight = theme === 'light';

    if (isLight) {
        html.classList.add('light');
    } else {
        html.classList.remove('light');
    }
    localStorage.setItem(KEY, isLight ? 'light' : 'dark');

    // Update topbar icon
    var sun = document.getElementById('iconSun');
    var moon = document.getElementById('iconMoon');
    if (sun) sun.style.display = isLight ? 'block' : 'none';
    if (moon) moon.style.display = isLight ? 'none' : 'block';

    updateAppearanceCards(isLight);
}

function updateAppearanceCards(isLight) {
    var cardDark  = document.getElementById('card-dark');
    var cardLight = document.getElementById('card-light');
    var checkDark  = document.getElementById('check-dark');
    var checkLight = document.getElementById('check-light');

    if (!cardDark) return;

    var blue = 'var(--blue-dark)';
    var off  = 'var(--border-alt)';

    if (isLight) {
        cardLight.style.borderColor = '#2563eb';
        cardDark.style.borderColor  = 'var(--border-alt)';
        checkLight.style.background = blue;
        checkLight.querySelector('svg').style.color = '#fff';
        checkDark.style.background  = off;
        checkDark.querySelector('svg').style.color = 'var(--text-muted)';
    } else {
        cardDark.style.borderColor  = '#2563eb';
        cardLight.style.borderColor = 'var(--border-alt)';
        checkDark.style.background  = blue;
        checkDark.querySelector('svg').style.color = '#fff';
        checkLight.style.background = off;
        checkLight.querySelector('svg').style.color = 'var(--text-muted)';
    }
}

// Init on load
(function() {
    var isLight = document.documentElement.classList.contains('light');
    updateAppearanceCards(isLight);
})();
</script>

{{-- ═══ SIPINTU CONNECTION STATUS ═══ --}}
<div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;box-shadow:var(--card-shadow);overflow:hidden">
    <div style="padding:22px 28px;border-bottom:1px solid var(--border-subtle);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
        <div>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                <div style="width:32px;height:32px;background:linear-gradient(135deg,rgba(99,102,241,.15),rgba(59,130,246,.15));border:1px solid rgba(99,102,241,.2);border-radius:9px;display:flex;align-items:center;justify-content:center">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#6366f1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div style="font-size:16px;font-weight:800;color:var(--text-primary)">SiPintu Gateway</div>
                <div id="sipintu-status-badge" style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:700;background:rgba(148,163,184,.12);border:1px solid rgba(148,163,184,.2);color:var(--text-muted)">
                    <span id="sipintu-dot" style="width:6px;height:6px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span id="sipintu-status-text">Belum dicek</span>
                </div>
            </div>
            <div style="font-size:13px;color:var(--text-muted)">Status koneksi ke SiPintu Identity & API Gateway.</div>
        </div>
        <button id="btn-cek-sipintu" onclick="cekKoneksiSipintu()" style="display:inline-flex;align-items:center;gap:7px;background:var(--bg-card-subtle);border:1.5px solid var(--border-alt);border-radius:10px;padding:9px 16px;font-size:12px;font-weight:700;color:var(--text-muted);cursor:pointer;transition:all .2s" onmouseover="this.style.borderColor='var(--blue)';this.style.color='var(--blue)'" onmouseout="this.style.borderColor='var(--border-alt)';this.style.color='var(--text-muted)'">
            <svg id="sipintu-spin" xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Cek Koneksi
        </button>
    </div>
    <div style="padding:18px 28px">
        <div id="sipintu-info-box" style="background:var(--bg-card-subtle);border:1px solid var(--border-subtle);border-radius:12px;padding:16px 18px">
            <div style="font-size:12px;color:var(--text-subtle);font-style:italic">Klik "Cek Koneksi" untuk memverifikasi status koneksi ke SiPintu Gateway.</div>
        </div>
        <div style="margin-top:12px;display:flex;gap:16px;flex-wrap:wrap">
            <div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-subtle);margin-bottom:3px">API URL</div>
                <code style="font-size:12px;color:var(--text-muted);background:var(--bg-card-subtle);border:1px solid var(--border-subtle);border-radius:6px;padding:3px 8px">{{ config('sipintu.api_url') }}</code>
            </div>
            <div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-subtle);margin-bottom:3px">Client ID</div>
                <code style="font-size:12px;color:var(--text-muted);background:var(--bg-card-subtle);border:1px solid var(--border-subtle);border-radius:6px;padding:3px 8px">{{ config('sipintu.client_id') ?: '(belum dikonfigurasi)' }}</code>
            </div>
        </div>
    </div>
</div>

<script>
async function cekKoneksiSipintu() {
    const badge    = document.getElementById('sipintu-status-badge');
    const dot      = document.getElementById('sipintu-dot');
    const statusTxt= document.getElementById('sipintu-status-text');
    const infoBox  = document.getElementById('sipintu-info-box');
    const spin     = document.getElementById('sipintu-spin');
    const btn      = document.getElementById('btn-cek-sipintu');

    // Loading state
    btn.disabled = true;
    spin.style.animation = 'spin 1s linear infinite';
    statusTxt.textContent = 'Memeriksa...';
    badge.style.background = 'rgba(245,158,11,.1)';
    badge.style.borderColor = 'rgba(245,158,11,.3)';
    badge.style.color = '#f59e0b';
    dot.style.background = '#f59e0b';
    infoBox.innerHTML = '<div style="font-size:12px;color:var(--text-subtle);font-style:italic">Menghubungi SiPintu Gateway...</div>';

    try {
        const res  = await fetch('{{ route("sipintu.status") }}', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();

        if (data.connected) {
            badge.style.background = 'rgba(16,185,129,.1)';
            badge.style.borderColor = 'rgba(16,185,129,.25)';
            badge.style.color = '#10b981';
            dot.style.background = '#10b981';
            dot.style.animation = 'pulse-dot 1.5s infinite';
            statusTxt.textContent = 'Connected';

            const clientConn = data.gateway_data?.client_connection ?? {};
            infoBox.innerHTML = `
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px">
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-subtle);margin-bottom:4px">Nama Klien</div>
                        <div style="font-size:13px;font-weight:600;color:var(--text-primary)">${clientConn.name ?? 'N/A'}</div>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-subtle);margin-bottom:4px">Status</div>
                        <div style="font-size:13px;font-weight:600;color:#10b981">${clientConn.status ?? 'online'}</div>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-subtle);margin-bottom:4px">Total Request API</div>
                        <div style="font-size:13px;font-weight:600;color:var(--text-primary)">${clientConn.total_api_requests ?? 0}</div>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-subtle);margin-bottom:4px">Terakhir Konek</div>
                        <div style="font-size:12px;color:var(--text-muted)">${clientConn.last_connected_at ? new Date(clientConn.last_connected_at).toLocaleString('id-ID') : '-'}</div>
                    </div>
                </div>`;
        } else {
            badge.style.background = 'rgba(239,68,68,.1)';
            badge.style.borderColor = 'rgba(239,68,68,.25)';
            badge.style.color = '#ef4444';
            dot.style.background = '#ef4444';
            statusTxt.textContent = 'Disconnected';
            infoBox.innerHTML = `<div style="display:flex;align-items:center;gap:8px;color:#ef4444;font-size:13px;font-weight:600">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                ${data.error ?? 'Tidak dapat terhubung ke SiPintu. Pastikan server SiPintu berjalan di ' + '{{ config("sipintu.api_url") }}.'}
            </div>`;
        }
    } catch (err) {
        badge.style.background = 'rgba(239,68,68,.1)';
        badge.style.borderColor = 'rgba(239,68,68,.25)';
        badge.style.color = '#ef4444';
        dot.style.background = '#ef4444';
        statusTxt.textContent = 'Error';
        infoBox.innerHTML = `<div style="color:#ef4444;font-size:13px">Gagal menghubungi endpoint status: ${err.message}</div>`;
    } finally {
        btn.disabled = false;
        spin.style.animation = '';
    }
}

// Keyframe untuk animasi spin dan pulse
const styleEl = document.createElement('style');
styleEl.textContent = `
    @keyframes spin { from { transform:rotate(0deg); } to { transform:rotate(360deg); } }
    @keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(1.3)} }
`;
document.head.appendChild(styleEl);
</script>
@endsection
