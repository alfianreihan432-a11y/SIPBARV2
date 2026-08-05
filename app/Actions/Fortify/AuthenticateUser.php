<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticateUser
{
    /**
     * Login berdasarkan role:
     *   admin  → email   + password
     *   guru   → nip     + nomor HP
     *   siswa  → nis     + nomor HP
     */
    public function __invoke(Request $request): ?User
    {
        return match ($request->input('role', 'admin')) {
            'siswa' => $this->loginSiswa($request),
            'guru'  => $this->loginGuru($request),
            default => $this->loginAdmin($request),
        };
    }

    /* ── Admin: email + password ── */
    private function loginAdmin(Request $request): ?User
    {
        $user = User::where('email', $request->input('email', ''))->first();

        if (! $user || ! Hash::check($request->input('password', ''), $user->password)) {
            return null;
        }

        return $user;
    }

    /* ── Guru: NIP + nomor HP ── */
    private function loginGuru(Request $request): ?User
    {
        $nip   = trim($request->input('nip', ''));
        $phone = $this->normalizePhone($request->input('phone', ''));

        if (! $nip || ! $phone) return null;

        $user = User::where('nip', $nip)->first();

        if (! $user) return null;

        // Bandingkan nomor HP (sudah dinormalisasi)
        if ($this->normalizePhone($user->phone ?? '') !== $phone) return null;

        return $user;
    }

    /* ── Siswa: NIS + nomor HP ── */
    private function loginSiswa(Request $request): ?User
    {
        $nis   = trim($request->input('nis', ''));
        $phone = $this->normalizePhone($request->input('phone', ''));

        if (! $nis || ! $phone) return null;

        $user = User::where('nis', $nis)->first();

        if (! $user) return null;

        if ($this->normalizePhone($user->phone ?? '') !== $phone) return null;

        return $user;
    }

    /**
     * Normalisasi nomor HP agar fleksibel:
     * 08xxx, 8xxx, +628xxx, 628xxx → semua jadi 08xxx
     */
    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone); // hapus non-digit

        if (str_starts_with($phone, '628')) {
            $phone = '0' . substr($phone, 2);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '0' . $phone;
        }

        return $phone;
    }
}
