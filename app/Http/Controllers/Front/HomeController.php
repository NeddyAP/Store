<?php

namespace App\Http\Controllers\Front;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $bestSeller = Cache::remember('home_best_seller', 3600, function () {
            return Product::orderBy('sold', 'desc')->take(6)->get();
        });

        $new = Cache::remember('home_new_products', 3600, function () {
            return Product::orderBy('created_at', 'desc')->take(6)->get();
        });

        return view('front.home.index', compact('bestSeller', 'new'));
    }
}
