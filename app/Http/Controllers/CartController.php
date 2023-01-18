<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Enums\ProductStatus;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $total = 0;

        $sessionCart = $request->session()->get('cart');

        if (is_array($sessionCart)) {
            foreach ($sessionCart as $value) {
                $total += $value['price'] * $value['quantity'];
            }
        }

        return view('cart.index', compact('total'));
    }

    public function add(Request $request)
    {
        $product = Product::whereStatus(ProductStatus::Active->value)->where('stock', '>', 0)->find($request->id);

        $addProduct = [
            'id' => $request->id, 
            'name' => $product->name,
            'image' => $product->image,
            'price' => $product->price,
            'stock' => $product->stock,
            'quantity' => $request->quantity, 
        ];

        $sessionCart = $request->session()->get('cart');

        if (!is_array($sessionCart)) {
            if ($addProduct['stock'] >= $addProduct['quantity']) {
                $request->session()->push('cart', $addProduct);
            } else {
                session()->flash('errorMessage', '※' . $addProduct['name'] .'は、在庫が不足しております。');
            }
        } else {
            $productExists = false;
            foreach ($sessionCart as $index => $value) {
                if ($value['id'] === $addProduct['id'] ) {
                    $productExists = true;
                    $AddQuantity = $value['quantity'] + $addProduct['quantity'];
                    if ($addProduct['stock'] >= $AddQuantity) {
                        $request->session()->put('cart.' . $index . '.quantity', $AddQuantity);
                    } else {
                        session()->flash('errorMessage', '※' . $addProduct['name'] .'は、在庫が不足しております。');
                    }
                    break;
                }
            }

            if ($productExists === false) {
                if ($addProduct['stock'] >= $addProduct['quantity']) {
                    $request->session()->push('cart', $addProduct);
                } else {
                    session()->flash('errorMessage', '※' . $addProduct['name'] .'は、在庫が不足しております。');
                }
            }
        }
        
        return to_route('cart.index');
    }

    public function update(Request $request)
    {
        $product = Product::whereStatus(ProductStatus::Active->value)->where('stock', '>', 0)->find($request->id);

        $updateProduct = [
            'id' => $request->id, 
            'stock' => $product->stock, 
            'quantity' => $request->quantity,
        ];

        $sessionCart = $request->session()->get('cart');

        foreach ($sessionCart as $index => $value) {
            if ($value['id'] === $updateProduct['id'] ) {
                $updateQuantity = $updateProduct['quantity'];
                if ($updateProduct['stock'] >= $updateQuantity) {
                    $request->session()->put('cart.' . $index . '.quantity', $updateQuantity);
                } else {
                    session()->flash('errorMessage', '※' . $value['name'] .'は、在庫が不足しております。');
                }
                break;
            }
        }

        return to_route('cart.index');
    }

    public function remove(Request $request)
    {
        $removeProduct = [
            'id' => $request->id, 
        ];

        $sessionCart = $request->session()->get('cart');

        foreach ($sessionCart as $index => $value) {
            if ($value['id'] === $removeProduct['id'] ) {
                unset($sessionCart[$index]);
                $request->session()->put('cart', array_values($sessionCart));
                if (empty($sessionCart)) {
                    $request->session()->forget('cart');
                }
                break;
            }
        }

        return to_route('cart.index');
    }

    public function destroy(Request $request) 
    {
        $request->session()->forget('cart');

        return to_route('cart.index');
    }
}
