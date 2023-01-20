<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Enums\ProductStatus;

class CartController extends Controller
{
    public function index()
    {
        return view('cart.index');
    }

    public function add(Request $request)
    {
        $product = Product::whereStatus(ProductStatus::Active->value)->find($request->id);

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

        if (!array_key_exists($addProduct['id'], session('cart', []))) {
            $this->checkStockForAdd($addProduct);
        } else {
            $AddQuantity = session('cart')[$addProduct['id']]['quantity'] + $addProduct['quantity'];
            $this->checkStockForUpdate($addProduct, $AddQuantity);
        }

        return to_route('products.index');
    }

    public function update(Request $request)
    {
        $product = Product::whereStatus(ProductStatus::Active->value)->find($request->id);

        if (is_null($product)) {
            session()->flash('errorMessage', __('cart.invalid_operation'));
            return to_route('cart.index');
        }

        $updateProduct = [
            'id' => $request->id, 
            'stock' => $product->stock, 
            'quantity' => $request->quantity,
        ];

        $this->checkStockForUpdate($updateProduct, $updateProduct['quantity']);

        return to_route('cart.index');
    }

    public function remove(Request $request)
    {
        $removeProduct = [
            'id' => $request->id, 
        ];

        session()->forget('cart.' . $removeProduct['id']);

        if (empty(session('cart'))) {
            session()->forget('cart');
        }

        return to_route('cart.index');
    }

    public function destroy() 
    {
        session()->forget('cart');

        return to_route('cart.index');
    }

    private function checkStockForAdd(array $product)
    {
        if ($product['stock'] >= $product['quantity']) {
            session()->put('cart.' . $product['id'], $product);
            session()->flash('successMessage', __('cart.add_success'));
        } else {
            session()->flash('errorMessage', __('cart.add_failed'));
        }
    }

    private function checkStockForUpdate(array $product, int $quantity)
    {
        if ($product['stock'] >= $quantity) {
            session()->put('cart.' . $product['id'] . '.quantity', $quantity);
            session()->flash('successMessage', __('cart.add_success'));
        } else {
            session()->put('cart.' . $product['id'] . '.quantity', $product['stock']);
            session()->flash('errorMessage', __('cart.add_failed'));
        }
    }
}
