<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperadminRestrictApprove
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is superadmin
        if (Auth::check() && Auth::user()->hasRole('superadmin')) {
            // Superadmin cannot access approve/reject routes except for reports
            $allowedRoutes = [
                'superadmin.laporan-jurusan.approve',
                'superadmin.laporan-jurusan.reject',
                'admin.laporan-jurusan.approve',
                'admin.laporan-jurusan.reject',
            ];

            $currentRouteName = $request->route() ? $request->route()->getName() : null;

            // Check if current route is an approve/reject route but not in allowed list
            if ($currentRouteName && (str_contains($currentRouteName, 'approve') || str_contains($currentRouteName, 'reject'))) {
                if (!in_array($currentRouteName, $allowedRoutes)) {
                    abort(403, 'Superadmin tidak memiliki izin untuk melakukan approve/reject pada halaman ini.');
                }
            }
        }

        return $next($request);
    }
}