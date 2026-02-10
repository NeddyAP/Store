<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at','desc')->paginate(6);
        $random = Product::inRandomOrder()->take(20)->get();
        return view('front.shop.index', compact('products', 'random'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $random = Product::inRandomOrder()->get();
        return view('front.shop.single.index', compact('product', 'random'));
    }
}
