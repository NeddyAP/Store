<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Services\ProductImageService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productImageService;

    public function __construct(ProductImageService $productImageService)
    {
        $this->productImageService = $productImageService;
        $products = Product::orderBy('created_at', 'desc')->paginate(18);
    }

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


        $filename = $this->productImageService->handleImageUpload($request->file('img'), $request->category);

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
            $this->productImageService->deleteImages($product->category, $product->img);
            $filename = $this->productImageService->handleImageUpload($request->file('img'), $request->category);

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
