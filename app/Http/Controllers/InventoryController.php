<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        // Check if user is superadmin and return appropriate layout
        if ($request->user()?->hasRole('superadmin')) {
            return view('pages.superadmin.inventory');
        }

        return view('inventory.index');
    }
}
