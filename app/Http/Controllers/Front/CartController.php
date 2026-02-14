<?php

namespace App\Http\Controllers\Front;

use App\Models\Cart;
use App\Models\Product;
use Auth;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index()
    {
        $carts = Cart::forUser(Auth::user()->id)->get();

        return view('front.cart.index', compact('carts'));
    }

    public function add(Request $request, $id)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $status = Cart::where('id_user', Auth::user()->id)->where('id_product', $id)->first();
        $product = Product::findOrFail($id);
        $price = $product->new_price ?? $product->price;

        if ($status) {
            $newQty = $status->qty + $request->qty;
            $status->update([
                'qty' => $newQty,
                'total' => $newQty * $price,
            ]);
        } else {
            Cart::create([
                'id_user' => Auth::user()->id,
                'id_product' => $id,
                'color' => $request->color,
                'qty' => $request->qty,
                'total' => $price * $request->qty,
            ]);
        }

        return redirect()->back()->with('success', 'Artikel Berhasil Ditambahkan');
    }

    public function delete($id)
    {
        $cart = Cart::where('id', $id)->where('id_user', Auth::user()->id)->first();
        if ($cart) {
            $cart->delete();
        }

        return redirect()->back();
    }

    public function checkout()
    {
        $carts = Cart::forUser(Auth::user()->id)->get();

        return view('front.cart.checkout', compact('carts'));
    }
}
