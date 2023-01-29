<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Enums\ProductStatus;
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
        $search = $request->search;
        $query = Product::search($search);
        $validProducts = $query->whereStatus(ProductStatus::Active->value);

        $sort = $request->sort;
        if ($sort === "popular" or is_null($sort)) {
            $products = $validProducts->withCount([
                'orderDetails AS totalQuantity' => function ($query) {
                    $query->select(DB::raw("SUM(quantity)"));
                }
            ])->orderBy('totalQuantity', 'desc')->get();
        }
        if ($sort === "new") {
            $products = $validProducts->orderBy('id', 'desc')->get();
        }
        if ($sort === "price") {
            $products = $validProducts->orderBy('price', 'asc')->get();
        }

        return view('products.index', compact('products', 'search', 'sort'));
    }
}
