<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;
use App\Enums\ProductStatus;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $maxRecords = 5;

        $search = $request->search;
        $query = Product::search($search);

        $products = $query->orderBy('id', 'desc')->paginate($maxRecords);

        return view('admin.products.index', compact('products', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $productStatuses = ProductStatus::cases();

        return view('admin.products.create', compact('productStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $fileName = Carbon::now()->format("YmdHis") . '_' . $request->file('image')->getClientOriginalName();

        $request->file('image')->storeAs('public/image', $fileName);

        Product::create([
            'name' => $request->name,
            'image' => $fileName,
            'introduction' => $request->introduction,
            'price' => $request->price,
            'stock' => $request->stock,
            'status' => $request->status,
        ]);

        return to_route('admin.products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        $productStatus = ProductStatus::from($product->status);

        return view('admin.products.show', compact('product', 'productStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);

        $productStatuses = ProductStatus::cases();

        return view('admin.products.edit', compact('product', 'productStatuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $product = Product::find($id);

        Storage::delete('public/image/' . $product->image);

        $fileName = Carbon::now()->format("YmdHis") . '_' . $request->file('image')->getClientOriginalName();

        $request->file('image')->storeAs('public/image', $fileName);

        $product->name = $request->name;
        $product->image = $fileName;
        $product->introduction = $request->introduction;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->status = $request->status;
        $product->save();

        return to_route('admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $product = Product::find($id);

            $product->delete();

            Storage::delete('public/image/' . $product->image);
        });

        return to_route('admin.products.index');
    }
}
