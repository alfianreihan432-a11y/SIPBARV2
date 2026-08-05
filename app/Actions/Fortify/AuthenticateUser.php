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
     *   guru   → nip     + tanggal lahir
     *   siswa  → nis     + tanggal lahir
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

    /* ── Guru: NIP + tanggal lahir ── */
    private function loginGuru(Request $request): ?User
    {
        $nip = trim($request->input('nip', ''));
        $tanggal_lahir = $request->input('tanggal_lahir', '');

        if (! $nip || ! $tanggal_lahir) return null;

        $user = User::where('nip', $nip)->first();

        if (! $user) return null;

        // Bandingkan tanggal lahir
        if ($user->tanggal_lahir !== $tanggal_lahir) return null;

        return $user;
    }

    /* ── Siswa: NIS + tanggal lahir ── */
    private function loginSiswa(Request $request): ?User
    {
        $nis = trim($request->input('nis', ''));
        $tanggal_lahir = $request->input('tanggal_lahir', '');

        if (! $nis || ! $tanggal_lahir) return null;

        $user = User::where('nis', $nis)->first();

        if (! $user) return null;

        // Bandingkan tanggal lahir
        if ($user->tanggal_lahir !== $tanggal_lahir) return null;

        return $user;
    }
}
