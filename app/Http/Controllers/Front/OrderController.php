<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\orderDetail;
use App\Models\Shipping;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(StoreOrderRequest $request)
    {
        $userId = Auth::id();
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $userId): void {
            $shipping = Shipping::create([
                'id_user' => $userId,
                'country' => $validated['country'],
                'name' => $validated['name'],
                'company_name' => $validated['company_name'] ?? null,
                'address' => isset($validated['address2']) && $validated['address2'] !== ''
                    ? $validated['address'].', '.$validated['address2']
                    : $validated['address'],
                'province' => $validated['province'],
                'zip' => $validated['zip'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'notes' => isset($validated['notes']) ? strip_tags($validated['notes']) : null,
            ]);

            $order = Order::create([
                'code' => 'TCP-'.Str::random(5).now()->format('-mdY'),
                'total' => $validated['total'],
                'id_user' => $userId,
                'id_shipping' => $shipping->id,
                'status_product' => 'Proccess',
                'status_user' => 'Proccess',
            ]);

            $allCart = Cart::forUser($userId);
            $carts = $allCart->get();

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

            $allCart->delete();
        });

        return view('front.order.thankyou');
    }
}
