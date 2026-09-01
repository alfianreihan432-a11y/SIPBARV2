<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckLoginRestriction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('sipbar.login_restriction.enabled', true)) {
            return $next($request);
        }

        $user = $request->user();
        if (! $user) {
            return $next($request);
        }

        // 1. Super Admin & Admin: Selalu diizinkan
        if ($user->hasAnyRole(['admin', 'super-admin']) || $user->hasRole('super_admin')) {
            return $next($request);
        }

        // 2. Guru: Selalu diizinkan
        if ($user->hasRole('guru')) {
            return $next($request);
        }

        // 3. Siswa: Cek apakah ada di dalam whitelist
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
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => config(
                    'sipbar.login_restriction.rejection_message',
                    'Akses sementara dibatasi. Silakan hubungi admin sekolah untuk informasi lebih lanjut.'
                ),
            ]);
        }

        return $next($request);
    }
}
