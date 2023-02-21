<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductRequestForStore;
use App\Http\Requests\ProductRequestForUpdate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * 商品の一覧を表示する。
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $maxRecords = 5;

        $search = $request->search;
        $query = Product::search($search);

        $products = $query->orderBy('id', 'desc')->paginate($maxRecords);

        return view('admin.products.index', compact('products', 'search'));
    }

    /**
     * 新規商品の作成フォームを表示する。
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.products.create');
    }

    /**
     * 新しく作成された商品をストレージに格納する。
     *
     * @param ProductRequestForStore $request
     * @return RedirectResponse
     */
    public function store(ProductRequestForStore $request): RedirectResponse
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
     * 指定された商品を表示する。
     *
     * @param [type] $id
     * @return View
     */
    public function show($id): View
    {
        $product = Product::find($id);

        return view('admin.products.show', compact('product'));
    }

    /**
     * 指定された商品を編集するためのフォームを表示する。
     *
     * @param [type] $id
     * @return View
     */
    public function edit($id): View
    {
        $product = Product::find($id);

        return view('admin.products.edit', compact('product'));
    }

    /**
     * ストレージ内の指定された商品を更新する。
     *
     * @param ProductRequestForUpdate $request
     * @param [type] $id
     * @return RedirectResponse
     */
    public function update(ProductRequestForUpdate $request, $id): RedirectResponse
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
     * 指定された商品をストレージから削除する。
     *
     * @param [type] $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $product = Product::find($id);

        $product->delete();

        return to_route('admin.products.index');
    }

    /**
     * ファイル名を取得する。
     *
     * @param Request $request
     * @return string
     */
    private function getFileName(Request $request): string
    {
        $fileName = Carbon::now()->format("YmdHis") . '_' . $request->file('image')->getClientOriginalName();

        return $fileName;
    }
}
