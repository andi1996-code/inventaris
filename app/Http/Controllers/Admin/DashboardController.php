<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        //jumlah product
        $totalProduct = Product::count();
        return view('pages.dashboard', compact('totalProduct'));
    }
}
