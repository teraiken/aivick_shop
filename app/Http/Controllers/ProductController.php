<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
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
        $sortType = $request->sortType;

        $products = $this->searchKeywordAndSortBy($search, $sortType);

        return view('products.index', compact('products', 'search', 'sortType'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::onSale()->find($id);

        if (is_null($product)) {
            session()->flash('errorMessage', __('cart.end_of_sale'));
            return to_route('products.index');
        }

        return view('products.show', compact('product'));
    }

    private function searchKeywordAndSortBy(?String $search, ?String $sortType): Collection
    {
        $query = Product::search($search);
        $onSaleProducts = $query->onSale();

        if ($sortType === "popular" or is_null($sortType)) {
            $products = $onSaleProducts->withCount([
                'orderDetails AS totalQuantity' => function ($query) {
                    $query->select(DB::raw("SUM(quantity)"));
                }
            ])->orderBy('totalQuantity', 'desc')->get();
        }
        if ($sortType === "new") {
            $products = $onSaleProducts->orderBy('start_date', 'desc')->get();
        }
        if ($sortType === "price") {
            $products = $onSaleProducts->orderBy('price', 'asc')->get();
        }

        return $products;
    }
}
