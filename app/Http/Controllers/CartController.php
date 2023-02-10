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
        if (array_key_exists($request->id, session('cart', []))) {
            $request->quantity += session('cart')[$request->id]['quantity'];
            $this->update($request);
            return to_route('products.index');
        }

        $product = Product::onSale()->find($request->id);

        if (is_null($product)) {
            session()->flash('errorMessage', __('cart.invalid_operation'));
            return to_route('products.index');
        }

        $addProduct = [
            'id' => $request->id,
            'name' => $product->name,
            'image' => $product->image,
            'price' => $product->price,
            'stock' => $product->stock,
            'quantity' => $request->quantity,
        ];

        $this->checkStockForAdd($addProduct);

        return to_route('products.index');
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

    private function checkStockForAdd(array $product)
    {
        if ($product['stock'] === 0) {
            session()->flash('errorMessage', __('cart.out_of_stock'));
        } elseif ($product['stock'] < $product['quantity']) {
            $product['quantity'] = $product['stock'];
            session()->put('cart.' . $product['id'], $product);
            session()->flash('errorMessage', __('cart.stock_short'));
        } else {
            session()->put('cart.' . $product['id'], $product);
            session()->flash('successMessage', __('cart.add_success'));
        }
    }

    private function checkStockForUpdate(array $product)
    {
        if ($product['stock'] === 0) {
            session()->forget('cart.' . $product['id']);
            session()->flash('errorMessage', __('cart.out_of_stock'));
        } elseif ($product['stock'] < $product['quantity']) {
            session()->put('cart.' . $product['id'] . '.quantity', $product['stock']);
            session()->flash('errorMessage', __('cart.stock_short'));
        } else {
            session()->put('cart.' . $product['id'] . '.quantity', $product['quantity']);
            session()->flash('successMessage', __('cart.add_success'));
        }
    }
}
