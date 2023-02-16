<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        return view('cart.index');
    }

    public function add(Request $request)
    {
        $product = Product::onSale()->find($request->id);

        if (is_null($product)) {
            session()->flash('errorMessage', __('cart.invalid_operation'));
            return to_route('products.index');
        }

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

        if ($message === __('cart.out_of_stock')) {
            $stock = 0;
        } else {
            $stock = $product->stock - session('cart')[$product->id]['quantity'];
        }

        return response()->json([
            'stock' => $stock,
            'count' => count(session('cart')),
            'message' => $message,
        ]);
    }

    public function update(Request $request)
    {
        $product = Product::onSale()->find($request->id);

        if (is_null($product)) {
            session()->flash('errorMessage', __('cart.invalid_operation'));
            return to_route('cart.index');
        }

        $updateProduct = [
            'id' => $request->id,
            'stock' => $product->stock,
            'quantity' => $request->quantity,
        ];

        $this->checkStockForUpdate($updateProduct);

        return to_route('cart.index');
    }

    public function remove(Request $request)
    {
        session()->forget('cart.' . $request->id);

        return to_route('cart.index');
    }

    public function destroy()
    {
        session()->forget('cart');

        return to_route('cart.index');
    }

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
