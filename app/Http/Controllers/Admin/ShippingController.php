<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipping;
use App\Models\User;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        $shippings = Shipping::paginate(10);

        return view('admin.shipping.index', compact('shippings'));
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $shippings = Shipping::where('company_name', 'like', '%'.$search.'%')
            ->orWhere('country', 'like', '%'.$search.'%')
            ->orWhere('name', 'like', '%'.$search.'%')
            ->orWhere('address', 'like', '%'.$search.'%')
            ->orWhere('province', 'like', '%'.$search.'%')
            ->orWhere('zip', 'like', '%'.$search.'%')
            ->orWhere('email', 'like', '%'.$search.'%')
            ->orWhere('phone', 'like', '%'.$search.'%')
            ->paginate(10);

        return view('admin.shipping.index', compact('shippings', 'search'));
    }

    public function detail($id)
    {
        $shipping = Shipping::find($id);
        $user = User::find($shipping->id_user);

        return view('admin.shipping.detail', compact('shipping', 'user'));
    }
}
