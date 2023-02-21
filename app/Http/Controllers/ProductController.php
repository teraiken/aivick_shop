<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * リソースの一覧を表示する。
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $search = $request->search;
        $sortType = $request->sortType;

        $products = $this->searchKeywordAndSortBy($search, $sortType);

        return view('products.index', compact('products', 'search', 'sortType'));
    }

    /**
     * 指定されたリソースを表示する。
     *
     * @param [type] $id
     * @return RedirectResponse|View
     */
    public function show($id): RedirectResponse|View
    {
        $product = Product::onSale()->find($id);

        if (is_null($product)) {
            session()->flash('errorMessage', __('cart.end_of_sale'));
            return to_route('products.index');
        }

        return view('products.show', compact('product'));
    }

    /**
     * キーワード検索と並び替えを行う。
     *
     * @param String|null $search
     * @param String|null $sortType
     * @return Collection
     */
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
