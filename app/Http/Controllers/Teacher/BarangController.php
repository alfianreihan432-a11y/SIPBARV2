<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\View\View;

class BarangController extends Controller
{
    /**
     * Display catalog of available items for teachers
     */
    public function index(): View
    {
        $items = Item::where('status', 'Tersedia')
            ->where('stock', '>', 0)
            ->with(['category', 'location'])
            ->latest()
            ->get();

        return view('pages.guru.barang', [
            'items' => $items,
        ]);
    }
}