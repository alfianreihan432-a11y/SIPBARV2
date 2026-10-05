<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckLoginRestriction
{
    /**
     * Tangani request yang masuk.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Login diizinkan selama kredensial valid; pemeriksaan akses berbasis role ditangani oleh
        // middleware route setelah autentikasi. Middleware ini tidak boleh menolak login secara langsung.
        return $next($request);
    }
}
