<?php

namespace App\Http\Controllers\Front;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $bestSeller = Product::orderBy('sold', 'desc')->take(6)->get();
        $new = Product::orderBy('created_at', 'desc')->take(6)->get();

        return view('front.home.index', compact('bestSeller', 'new'));
    }
}
