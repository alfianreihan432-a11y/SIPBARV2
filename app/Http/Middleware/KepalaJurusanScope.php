<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KepalaJurusanScope
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Check if user is kepala jurusan
        if ($user && $user->hasRole('kepala_jurusan')) {
            // Ensure kepala jurusan has a jurusan assigned
            if (!$user->jurusan_id) {
                abort(403, 'Anda belum ditugaskan ke jurusan manapun. Hubungi administrator.');
            }

            // Share jurusan_id with all views for scoping
            view()->share('kajur_jurusan_id', $user->jurusan_id);
            view()->share('kajur_jurusan_nama', $user->jurusan->nama);
        }

        return $next($request);
    }
}
