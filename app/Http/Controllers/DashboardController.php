<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Super Admin - redirect to superadmin dashboard
        if ($user->hasAnyRole(['superadmin', 'super-admin', 'super_admin'])) {
            return redirect()->route('superadmin.dashboard');
        }

        if ($user->hasRole('siswa')) {
            return view('dashboard-siswa');
        }

        if ($user->hasRole('guru')) {
            return view('dashboard-guru');
        }

        if ($user->hasRole('kepala_jurusan')) {
            return redirect()->route('kajur.dashboard');
        }

        // admin / petugas
        return view('dashboard');
    }
}
