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
            // Sementara pakai dashboard admin, nanti bisa dibuat terpisah
            return view('dashboard');
        }

        // admin / super-admin / petugas
        return view('dashboard');
    }
}
