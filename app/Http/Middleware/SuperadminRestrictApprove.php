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
     * Superadmin has READ-ONLY access to most pages EXCEPT:
     * 1. All Report pages (approve/reject allowed)
     * 2. Settings page (full access)
     * 3. Users page (full access)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is superadmin
        if (Auth::check() && Auth::user()->hasRole('superadmin')) {
            $currentRouteName = $request->route() ? $request->route()->getName() : null;

            if (!$currentRouteName) {
                return $next($request);
            }

            // ═══════════════════════════════════════════════════════════
            // ALLOWED ROUTES (Full Access for Superadmin)
            // ═══════════════════════════════════════════════════════════
            
            // 1. All Report Routes (laporan-jurusan, laporan-admin, reports)
            if (str_contains($currentRouteName, 'laporan') || 
                str_contains($currentRouteName, 'reports')) {
                return $next($request); // ✅ Full access to reports
            }

            // 2. Settings Routes
            if (str_contains($currentRouteName, 'settings')) {
                return $next($request); // ✅ Full access to settings
            }

            // 3. Users Routes
            if (str_contains($currentRouteName, 'users')) {
                return $next($request); // ✅ Full access to users
            }

            // 4. Inventory + import routes (full access to manage items)
            if (str_contains($currentRouteName, 'inventory') ||
                str_contains($currentRouteName, 'items.import')) {
                return $next($request); // ✅ Full access to item management and KIBB imports
            }

            // 5. Dashboard & Read-only routes (GET)
            if ($request->isMethod('GET') && 
                (str_contains($currentRouteName, 'dashboard') ||
                 str_contains($currentRouteName, 'qr-scanner') ||
                 str_contains($currentRouteName, 'loans') ||
                 str_contains($currentRouteName, 'returns') ||
                 str_contains($currentRouteName, 'statistics'))) {
                return $next($request); // ✅ Read-only access
            }

            // ═══════════════════════════════════════════════════════════
            // BLOCKED ROUTES (Mutating Operations)
            // ═══════════════════════════════════════════════════════════
            
            // Block all mutating operations on non-allowed pages
            $mutatingActions = [
                'create', 'store', 'edit', 'update', 'delete', 'destroy',
                'approve', 'reject', 'save', 'cancel', 'restore',
                'mark', 'toggle', 'sync', 'import', 'export'
            ];

            foreach ($mutatingActions as $action) {
                if (str_contains($currentRouteName, $action)) {
                    abort(403, 'Superadmin tidak memiliki izin untuk melakukan operasi ini. Hanya halaman Laporan, Pengaturan, dan Pengguna yang dapat dimodifikasi.');
                }
            }

            // Block POST/PUT/PATCH/DELETE requests on non-allowed routes
            if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                abort(403, 'Superadmin tidak memiliki izin untuk melakukan operasi ini. Hanya halaman Laporan, Pengaturan, dan Pengguna yang dapat dimodifikasi.');
            }
        }

        return $next($request);
    }
}