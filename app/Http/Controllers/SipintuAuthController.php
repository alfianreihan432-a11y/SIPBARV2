<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SipintuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/**
 * SipintuAuthController
 *
 * Menangani alur OAuth 2.0 SSO "Login dengan SiPintu".
 * Alur 4 langkah:
 *   1. redirect()  → Arahkan user ke SiPintu /oauth/authorize
 *   2. callback()  → Tangkap code, validasi state
 *   3. Exchange code → access_token (via SipintuService)
 *   4. Fetch user profile → login/create user → redirect dashboard
 */
class SipintuAuthController extends Controller
{
    public function __construct(protected SipintuService $sipintu) {}

    // ─────────────────────────────────────────────────────────
    // LANGKAH 1: Redirect ke SiPintu Authorization Endpoint
    // ─────────────────────────────────────────────────────────

    /**
     * GET /oauth/sipintu
     * Generate state CSRF, simpan di session, redirect ke SiPintu.
     */
    public function redirect(Request $request)
    {
        // Pastikan kredensial terkonfigurasi
        if (empty(config('sipintu.client_id'))) {
            return redirect()->route('login')
                ->with('sipintu_error', 'Integrasi SiPintu belum dikonfigurasi. Hubungi administrator.');
        }

        // Generate random state untuk proteksi CSRF
        $state = Str::random(40);
        $request->session()->put('sipintu_oauth_state', $state);

        $authUrl = $this->sipintu->getAuthorizationUrl($state);

        return redirect()->away($authUrl);
    }

    // ─────────────────────────────────────────────────────────
    // LANGKAH 2-4: Tangkap callback, exchange token, login user
    // ─────────────────────────────────────────────────────────

    /**
     * GET /oauth/callback
     * Terima code dari SiPintu, tukar dengan token, login/buat user.
     */
    public function callback(Request $request)
    {
        // ── Cek apakah ada error dari SiPintu ──
        if ($request->has('error')) {
            $errorMsg = $request->get('error_description', $request->get('error', 'Login via SiPintu dibatalkan.'));
            return redirect()->route('login')
                ->with('sipintu_error', 'SiPintu: ' . $errorMsg);
        }

        // ── Validasi state (proteksi CSRF) ──
        $sessionState = $request->session()->pull('sipintu_oauth_state');
        $returnedState = $request->get('state');

        if (empty($sessionState) || $sessionState !== $returnedState) {
            Log::warning('SiPintu OAuth: state mismatch', [
                'session_state'  => $sessionState,
                'returned_state' => $returnedState,
                'ip'             => $request->ip(),
            ]);
            return redirect()->route('login')
                ->with('sipintu_error', 'Keamanan: state tidak cocok. Silakan coba login kembali.');
        }

        // ── Ambil authorization code ──
        $code = $request->get('code');
        if (empty($code)) {
            return redirect()->route('login')
                ->with('sipintu_error', 'Authorization code tidak diterima dari SiPintu.');
        }

        // ── LANGKAH 3: Tukar code dengan access token ──
        $tokenResult = $this->sipintu->exchangeCodeForToken($code);

        if (! $tokenResult['success']) {
            Log::error('SiPintu token exchange failed', ['error' => $tokenResult['error']]);
            return redirect()->route('login')
                ->with('sipintu_error', 'Gagal mendapatkan token dari SiPintu: ' . $tokenResult['error']);
        }

        $accessToken = $tokenResult['access_token'];

        // ── LANGKAH 4: Ambil profil user dari SiPintu ──
        $profileResult = $this->sipintu->getUserProfile($accessToken);

        if (! $profileResult['success']) {
            Log::error('SiPintu getUserProfile failed', ['error' => $profileResult['error']]);
            return redirect()->route('login')
                ->with('sipintu_error', 'Gagal mengambil data pengguna dari SiPintu: ' . $profileResult['error']);
        }

        $sipintuUser = $profileResult['data'];

        // Pastikan email tersedia dari SiPintu
        $email = $sipintuUser['email'] ?? null;
        if (empty($email)) {
            return redirect()->route('login')
                ->with('sipintu_error', 'SiPintu tidak mengembalikan alamat email pengguna. Pastikan scope email diizinkan.');
        }

        // ── Cari atau buat user di database SIPBAR ──
        $user = User::where('email', $email)->first();

        if (! $user) {
            // Auto-create user baru dari data SiPintu
            $user = User::create([
                'name'              => $sipintuUser['name'] ?? $sipintuUser['username'] ?? explode('@', $email)[0],
                'email'             => $email,
                'password'          => Hash::make(Str::random(32)), // password acak — user login via SSO
                'email_verified_at' => now(), // email sudah terverifikasi via SiPintu
            ]);

            // Assign role default (siswa atau sesuai data SiPintu)
            $sipintuRole = strtolower($sipintuUser['role'] ?? $sipintuUser['type'] ?? 'siswa');
            $localRole = match($sipintuRole) {
                'admin', 'administrator' => 'admin',
                'guru', 'teacher'        => 'guru',
                default                  => 'siswa',
            };

            if (Role::where('name', $localRole)->exists()) {
                $user->assignRole($localRole);
            }

            Log::info("SiPintu SSO: user baru dibuat [{$email}] dengan role [{$localRole}]");
        } else {
            // Update nama jika berubah di SiPintu
            if (! empty($sipintuUser['name']) && $user->name !== $sipintuUser['name']) {
                $user->update(['name' => $sipintuUser['name']]);
            }
        }

        // ── Simpan token di session (opsional — untuk future API calls) ──
        $request->session()->put('sipintu_access_token',  $accessToken);
        $request->session()->put('sipintu_refresh_token', $tokenResult['refresh_token'] ?? null);

        // ── Login user ke Laravel Auth ──
        Auth::login($user, remember: true);
        $request->session()->regenerate();

        Log::info("SiPintu SSO: login berhasil [{$email}]");

        // ── Redirect ke dashboard sesuai role ──
        $roles = $user->getRoleNames();

        if ($roles->contains('admin')) {
            return redirect()->route('dashboard');
        } elseif ($roles->contains('guru')) {
            return redirect()->route('teacher.dashboard');
        } else {
            return redirect()->route('student.dashboard');
        }
    }
}
