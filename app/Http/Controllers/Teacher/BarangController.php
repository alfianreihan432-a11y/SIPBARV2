<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BarangController extends Controller
{
    /**
     * Display catalog of available items for teachers
     */
    public function index(Request $request): View
    {
        $query = Item::where('status', 'Tersedia')
            ->where('stock', '>', 0)
            ->with(['category', 'location']);

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categoryFilter = $request->query('categoryFilter', $request->query('category', ''));
        if (!empty($categoryFilter)) {
            $query->where('category_id', $categoryFilter);
        }

        $items = $query->latest()->get();
        $categories = Category::orderBy('name')->get();

        return view('pages.guru.barang', [
            'items' => $items,
            'categories' => $categories,
            'search' => $search,
            'categoryFilter' => (string) $categoryFilter,
        ]);
    }
}