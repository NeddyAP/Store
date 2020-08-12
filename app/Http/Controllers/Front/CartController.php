<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index()
    {
        $carts = Cart::where('id_user', Auth::user()->id)->join('products','products.id','=','carts.id_product')
        ->select('carts.*','products.name','products.price','products.new_price','products.img','products.category')->get();
        // return $carts;
        return view('front.cart.index', compact('carts'));
    }

    public function add(Request $request, $id)
    {
        $status = Cart::where('id_user',Auth::user()->id)->Where('id_product',$id)->first();
        $product = Product::find($id);
        // return $product;
        if ($status) {
            $status->update([
                'qty' => $status->qty + $request->qty
            ]);
        }else{
            $cart = Cart::create([
                'id_user' => Auth::user()->id,
                'id_product' => $id,
                'color' => $request->color,
                'qty' => $request->qty,
                'total' => $product->price * $request->qty,
                ]);
        }

        // return $cart;
        return redirect()->back()->with('danger', 'Artikel Berhasil Dihapus');
    }
    public function delete($id)
    {
        // return $id;
        Cart::find($id)->delete();
        return redirect()->back();
    }

    public function checkout()
    {
        $carts = Cart::where('id_user', Auth::user()->id)->join('products','products.id','=','carts.id_product')
        ->select('carts.*','products.name','products.price','products.new_price','products.img','products.category')->get();
        return view('front.cart.checkout', compact('carts'));
    }
}
