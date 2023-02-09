<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductRequestForStore;
use App\Http\Requests\ProductRequestForUpdate;
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
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequestForStore $request)
    {
        $fileName = $this->getFileName($request);

        $request->file('image')->storeAs('public/image', $fileName);

        Product::create([
            'name' => $request->name,
            'image' => $fileName,
            'introduction' => $request->introduction,
            'price' => $request->price,
            'stock' => $request->stock,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
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

        return view('admin.products.show', compact('product'));
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

        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequestForUpdate $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            $product = Product::find($id);

            if (file_exists($request->file('image'))) {
                Storage::delete('public/image/' . $product->image);
                $fileName = $this->getFileName($request);
                $request->file('image')->storeAs('public/image', $fileName);
                $product->image = $fileName;
            }

            $product->name = $request->name;
            $product->introduction = $request->introduction;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->start_date = $request->start_date;
            $product->end_date = $request->end_date;
            $product->save();
        });

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
        $product = Product::find($id);

        $product->delete();

        return to_route('admin.products.index');
    }

    private function getFileName(Request $request): string
    {
        $fileName = Carbon::now()->format("YmdHis") . '_' . $request->file('image')->getClientOriginalName();

        return $fileName;
    }
}
