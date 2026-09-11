<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('siswa')) {
            return view('dashboard-siswa');
        }

        if ($user->hasRole('guru')) {
            return view('dashboard-guru');
        }

        if ($user->hasRole('kepala_jurusan')) {
            return redirect()->route('kajur.dashboard');
        }

        // admin / super-admin / petugas
        return view('dashboard');
    }
}
