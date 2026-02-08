<?php

namespace App\Http\Controllers\Front;

use App\Models\Cart;
use App\Models\Order;
use App\Models\orderDetail;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request, $id)
    {
        $userId = Auth::id();

        // Shipping
        $shipping = Shipping::create([
            'id_user' => $userId,
            'country' => $request->country,
            'name' => $request->name,
            'company_name' => $request->company_name,
            'address' => $request->address.', '.$request->address2,
            'province' => $request->province,
            'zip' => $request->zip,
            'email' => $request->email,
            'phone' => $request->phone,
            'notes' => $request->notes ? strip_tags($request->notes) : null,
        ]);

        // Order
        $order = Order::create([
            'code' => 'TCP-'.Str::random(5).now()->format('-mdY'),
            'total' => $request->total,
            'id_user' => $userId,
            'id_shipping' => $shipping->id,
            'status_product' => 'Proccess',
            'status_user' => 'Proccess',
        ]);

        // Order Details
        $all_cart = Cart::forUser($userId);
        $carts = $all_cart->get();
        foreach ($carts as $cart) {
            orderDetail::create([
                'id_order' => $order->id,
                'id_user' => $userId,
                'id_product' => $cart->id_product,
                'color' => $cart->color,
                'qty' => $cart->qty,
                'total' => $cart->total,
            ]);
        }

        $shipping->save();
        $order->save();
        $all_cart->delete();

        return view('front.order.thankyou');
    }
}
