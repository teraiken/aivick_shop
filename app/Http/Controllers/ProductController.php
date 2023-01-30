<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Enums\ProductStatus;
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

    private function searchKeywordAndSortBy(?String $search, ?String $sortType): Collection
    {
        $query = Product::search($search);
        $validProducts = $query->whereStatus(ProductStatus::Active->value);

        if ($sortType === "popular" or is_null($sortType)) {
            $products = $validProducts->withCount([
                'orderDetails AS totalQuantity' => function ($query) {
                    $query->select(DB::raw("SUM(quantity)"));
                }
            ])->orderBy('totalQuantity', 'desc')->get();
        }
        if ($sortType === "new") {
            $products = $validProducts->orderBy('id', 'desc')->get();
        }
        if ($sortType === "price") {
            $products = $validProducts->orderBy('price', 'asc')->get();
        }

        return $products;
    }
}
