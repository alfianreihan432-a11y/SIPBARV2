<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticateUser
{
    /**
     * Login berdasarkan role:
     *   admin  → email + password
     *   siswa  → email + password
     *   guru   → email + password
     */
    public function __invoke(Request $request): ?User
    {
        $input = trim((string) $request->input('email', ''));
        $password = (string) $request->input('password', '');

        if ($input === '' || $password === '') {
            return null;
        }

        // 1. Cari exact match email
        $user = User::where('email', $input)->first();

        // 2. Jika belum ketemu, coba cari dengan domain @sipbar.sch.id atau NIP guru
        if (! $user) {
            $clean = strtolower(trim(str_replace('@sipbar.sch.id', '', $input)));

            // Coba cari via email @sipbar.sch.id
            $user = User::where('email', "{$clean}@sipbar.sch.id")->first();

            // Jika masih belum ketemu, coba cari via NIP (khusus guru)
            if (! $user) {
                $user = User::where('nip', $clean)->first();
            }

            // Jika masih belum ketemu dan input 8 digit (kasus tanggal lahir guru format terbalik)
            if (! $user && preg_match('/^\d{8}$/', $clean)) {
                // Jika input YYYYMMDD (19840514) -> coba DDMMYYYY (14051984)
                if (preg_match('/^(19\d{2}|20\d{2})(\d{2})(\d{2})$/', $clean, $m)) {
                    $reversed = $m[3] . $m[2] . $m[1]; // DDMMYYYY
                    $user = User::where('email', "{$reversed}@sipbar.sch.id")
                        ->orWhere('nip', 'like', "{$clean}%")
                        ->first();
                }
                // Jika input DDMMYYYY (14051984) -> coba YYYYMMDD (19840514)
                else {
                    $day = substr($clean, 0, 2);
                    $month = substr($clean, 2, 2);
                    $year = substr($clean, 4, 4);
                    $reversed = $year . $month . $day; // YYYYMMDD
                    $user = User::where('email', "{$reversed}@sipbar.sch.id")
                        ->orWhere('nip', 'like', "{$reversed}%")
                        ->first();
                }
            }
        }

        if (! $user || ! Hash::check($password, $user->password)) {
            return null;
        }

        $role = $request->input('role');
        $hasRole = ! $role || ($role === 'admin'
            ? $user->hasAnyRole(['admin', 'super-admin'])
            : $user->hasRole($role));

        if (! $role) {
            $hasRole = true;
        }

        if (! $hasRole) {
            return null;
        }

        // 3. Pengecekan Mode Pembatasan Akses / Maintenance Login
        if (config('sipbar.login_restriction.enabled', true)) {
            $isAdminOrSuperAdmin = $user->hasAnyRole(['admin', 'super-admin']) || $user->hasRole('super_admin');
            $isTeacher = $user->hasRole('guru');

            // Guru & Super Admin/Admin selalu diizinkan login
            if (! $isAdminOrSuperAdmin && ! $isTeacher) {
                $whitelist = config('sipbar.login_restriction.whitelisted_students', []);
                $userEmail = strtolower(trim($user->email));
                $userNis = strtolower(trim($user->nis ?? ''));

                $isWhitelisted = false;
                foreach ($whitelist as $item) {
                    $item = strtolower(trim($item));
                    if ($item === '') {
                        continue;
                    }

                    $itemNis = str_replace('@sipbar.sch.id', '', $item);
                    $itemEmail = str_contains($item, '@') ? $item : "{$item}@sipbar.sch.id";

                    if ($userEmail === $item || $userEmail === $itemEmail || ($userNis !== '' && ($userNis === $item || $userNis === $itemNis))) {
                        $isWhitelisted = true;
                        break;
                    }
                }

                if (! $isWhitelisted) {
                    throw ValidationException::withMessages([
                        'email' => config(
                            'sipbar.login_restriction.rejection_message',
                            'Akses sementara dibatasi. Silakan hubungi admin sekolah untuk informasi lebih lanjut.'
                        ),
                    ]);
                }
            }
        }

        return $user;
    }
}
