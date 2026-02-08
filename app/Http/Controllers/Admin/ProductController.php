<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use File;
use Image;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(18);
        return view('admin.product.index', compact('products'));
        // ->with('no', (request()->input('page', 1) - 1) * 5)
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $result = Product::where('name', 'like', "%" . $search . "%")
        ->orWhere('price', 'like', "%" . $search . "%")
        ->orWhere('category', 'like', "%" . $search . "%")
        ->orWhere('status', 'like', "%" . $search . "%")
        ->orderBy('created_at', 'desc')->paginate(18);
        $products = Product::orderBy('created_at', 'desc')->paginate(18);
        // dd($result);
        return view('admin.product.index', compact('result','products'));
    }

    public function create()
    {
        return view('admin.product.actions.input');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'price' => 'required|integer',
            'spec' => 'required|min:3',
            'qty' => 'required|integer',
            'desc' => 'required',
            'img' => 'required|image|max:2048'
        ]);


        $image = $request->file('img');
        $filename = \Carbon\Carbon::now()->timestamp . '_' . uniqid() . '.' . $image->getClientOriginalExtension();


        // Thumbnail Image
        $destinationPath = public_path('asset/'.$request->category.'/thumbnail');
        File::exists($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
        $canvas = Image::canvas(500, 800);

        $img = Image::make($image->path());
        $img->resize(400, 400, function ($constraint) {
            $constraint->aspectRatio();
        });
        $canvas->insert($img, 'center')->save($destinationPath.'/'.$filename);

        // Single Image
        $destinationPath = public_path('asset/'.$request->category.'/single');
        File::exists($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
        $img = Image::make($image->path());
        $img->resize(500, 500, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$filename);

        //////// 285
        $destinationPath = public_path('asset/'.$request->category.'/285');
        File::exists($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
        $img = Image::make($image->path());
        $img->resize(285, 400, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$filename);


        // Original Image
        $destinationPath = public_path('asset/'.$request->category.'/');
        $image->move($destinationPath, $filename);

        //

        // $image = $request->file('img');
        // $filename = \Carbon\Carbon::now()->timestamp . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        // $image->resize(100, 100, function ($constraint) {
        //     $constraint->aspectRatio();
        // })->move(public_path('asset/'.$request->category.'/'), $filename);
        $product = Product::create([
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'spec' => $request->spec,
            'qty' => $request->qty,
            'desc' => clean($request->desc),
            'color' => $request->color,
            'img' => $filename,
        ]);
        $tags = explode(",", $request->color);
        $product->tag($tags);

        // Product::create($request->all());
        return redirect(route('products.index'));
    }


    public function show(Product $product)
    {
        //
    }


    public function edit($id)
    {
        $products = Product::all();
        $product = $products->find($id);
        return view('admin.product.actions.edit', compact('product'));
    }


    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'price' => 'required|integer',
            'new_price' => 'integer',
            'spec' => 'required|min:3',
            'qty' => 'required|integer',
            'desc' => 'required',
            'color' => 'required',
            'img' => 'image|max:2048'
        ]);
        if ($request->hasFile('img')) {
            File::delete(public_path('asset/'.$product->category.'/thumbnail/'). $product->img);
            File::delete(public_path('asset/'.$product->category.'/285/'). $product->img);
            File::delete(public_path('asset/'.$product->category.'/'). $product->img);

            $image = $request->file('img');
            $filename = \Carbon\Carbon::now()->timestamp . '_' . uniqid() . '.' . $image->getClientOriginalExtension();


            // Thumbnail Image
            $destinationPath = public_path('asset/'.$request->category.'/thumbnail');

            File::exists($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);

            $canvas = Image::canvas(500, 800);

            $img = Image::make($image->path());
            $img->resize(400, 400, function ($constraint) {
            $constraint->aspectRatio();
            });
            // $canvas->save($destinationPath.'/'.$filename);
            $canvas->insert($img, 'center')->save($destinationPath.'/'.$filename);

            // 285
            $destinationPath = public_path('asset/'.$request->category.'/285');
            File::exists($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
            $img = Image::make($image->path());
            $img->resize(285, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$filename);

            // Single Image
            $destinationPath = public_path('asset/'.$request->category.'/single');
            File::exists($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
            $img = Image::make($image->path());
            $img->resize(500,500, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$filename);


            // Original Image
            $destinationPath = public_path('asset/'.$request->category.'/');
            $image->move($destinationPath, $filename);


            // $image = $request->file('img');
            // $filename = \Carbon\Carbon::now()->timestamp . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            // $image->move(public_path('asset/'.$request->category.'/'), $filename);
            $product->update([
                'img' => $filename,
            ]);
        }
        $product->update([
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'new_price' => $request->new_price,
            'spec' => $request->spec,
            'qty' => $request->qty,
            'desc' => clean($request->desc),
            'color' => $request->color,
        ]);
        $tags = explode(",", $request->color);
        $product->tag($tags);

        return redirect(route('products.index'));
    }

    public function trash($id)
    {
        $product = Product::find($id);
        $product->update([
            'status' => 'Trash'
        ]);
        return redirect(route('products.index'));
    }

    public function destroy($id)
    {
        Product::find($id)->delete();
        return redirect()->back();
    }
}
