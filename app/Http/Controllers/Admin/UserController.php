<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shipping;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('admin.user.index',compact('users'));
    }
    public function search(Request $request)
    {
        $search = $request->search;
        $users = User::where('name', 'like', "%" . $search . "%")
            ->orWhere('email', 'like', "%" . $search . "%")
            ->orWhere('phone', 'like', "%" . $search . "%")
            ->paginate(10);
        return view('admin.user.index',compact('users','search'));
    }
    public function detail($id)
    {
        $user = User::find($id);
        $shippings = Shipping::where('id_user', $user->id)->latest()->get();
        return view('admin.user.detail',compact('user','shippings'));
    }
    public function shippingSearch(Request $request, $id)
    {
        $user = User::find($id);
        $search = $request->search;
        $shippings = Shipping::where('id_user', $user->id)
            ->where(function ($query) use ($search) {
                $query->where('company_name', 'like', "%" . $search . "%")
                    ->orWhere('country', 'like', "%" . $search . "%")
                    ->orWhere('name', 'like', "%" . $search . "%")
                    ->orWhere('address', 'like', "%" . $search . "%")
                    ->orWhere('province', 'like', "%" . $search . "%")
                    ->orWhere('zip', 'like', "%" . $search . "%")
                    ->orWhere('email', 'like', "%" . $search . "%")
                    ->orWhere('phone', 'like', "%" . $search . "%");
            })->get();

        return view('admin.user.detail', compact('user', 'shippings', 'search'));
    }
}
