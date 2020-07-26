<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at','desc')->paginate(6);
        return view('front.shop.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::find($id);
        return view('front.shop.single.index', compact('product'));
    }
}
