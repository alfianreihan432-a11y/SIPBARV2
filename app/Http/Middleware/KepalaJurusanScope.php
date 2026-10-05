<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KepalaJurusanScope
{
    /**
     * Tangani request yang masuk.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Periksa apakah user adalah kepala jurusan
        if ($user && $user->hasRole('kepala_jurusan')) {
            // Pastikan kepala jurusan sudah ditugaskan ke jurusan
            if (!$user->jurusan_id) {
                abort(403, 'Anda belum ditugaskan ke jurusan manapun. Hubungi administrator.');
            }

            // Bagikan jurusan_id ke semua view untuk keperluan scope
            view()->share('kajur_jurusan_id', $user->jurusan_id);
            view()->share('kajur_jurusan_nama', $user->jurusan ? $user->jurusan->nama : 'Unknown');
        }

        return $next($request);
    }
}
