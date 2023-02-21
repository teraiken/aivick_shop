<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * カートの一覧を表示する。
     *
     * @return View
     */
    public function index(): View
    {
        return view('cart.index');
    }

    /**
     * カートに商品を追加する。
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $product = Product::onSale()->find($request->id);

        if (array_key_exists($request->id, session('cart', []))) {
            $request->quantity += session('cart')[$request->id]['quantity'];
            $updateProduct = [
                'id' => $request->id,
                'stock' => $product->stock,
                'quantity' => $request->quantity,
            ];

            $message = $this->checkStockForUpdate($updateProduct);
        } else {
            $addProduct = [
                'id' => $request->id,
                'name' => $product->name,
                'image' => $product->image,
                'price' => $product->price,
                'stock' => $product->stock,
                'quantity' => $request->quantity,
            ];

            $message = $this->checkStockForAdd($addProduct);
        }

        $stock = $product->stock - (session('cart')[$product->id]['quantity'] ?? 0);

        return response()->json([
            'stock' => $stock,
            'count' => count(session('cart')),
            'message' => $message,
        ]);
    }

    /**
     * カート内の商品の個数を更新する。
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $product = Product::onSale()->find($request->id);

        if (is_null($product)) {
            session()->forget('cart.' . $request->id);
            session()->flash('errorMessage', __('cart.end_of_sale'));
            return to_route('cart.index');
        }

        $updateProduct = [
            'id' => $request->id,
            'stock' => $product->stock,
            'quantity' => $request->quantity,
        ];

        $message = $this->checkStockForUpdate($updateProduct);

        if (!($message === __('cart.add_success'))) {
            session()->flash('errorMessage', $message);
        }

        return to_route('cart.index');
    }

    /**
     * カート内の商品を削除する。
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function remove(Request $request): RedirectResponse
    {
        session()->forget('cart.' . $request->id);

        return to_route('cart.index');
    }

    /**
     * カート内を空にする。
     *
     * @return RedirectResponse
     */
    public function destroy(): RedirectResponse
    {
        session()->forget('cart');

        return to_route('cart.index');
    }

    /**
     * 在庫を確認する。(追加用)
     *
     * @param array $product
     * @return string
     */
    private function checkStockForAdd(array $product): string
    {
        if ($product['stock'] === 0) {
            $message = __('cart.out_of_stock');
        } elseif ($product['stock'] < $product['quantity']) {
            $product['quantity'] = $product['stock'];
            session()->put('cart.' . $product['id'], $product);
            $message = __('cart.stock_short');
        } else {
            session()->put('cart.' . $product['id'], $product);
            $message = __('cart.add_success');
        }

        return $message;
    }

    /**
     * 在庫を確認する。(更新用)
     *
     * @param array $product
     * @return string
     */
    private function checkStockForUpdate(array $product): string
    {
        if ($product['stock'] === 0) {
            session()->forget('cart.' . $product['id']);
            $message = __('cart.out_of_stock');
        } elseif ($product['stock'] < $product['quantity']) {
            session()->put('cart.' . $product['id'] . '.quantity', $product['stock']);
            $message = __('cart.stock_short');
        } else {
            session()->put('cart.' . $product['id'] . '.quantity', $product['quantity']);
            $message = __('cart.add_success');
        }

        return $message;
    }
}
