<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Charge;
use App\Helpers\Calculator;
use App\Http\Requests\AddressRequest;

class OrderController extends Controller
{
    public function index()
    {
        $maxRecords = 5;

        $orders = Order::whereUserId(Auth::id())->orderBy('id', 'desc')->paginate($maxRecords);

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        if (empty(session('cart'))) {
            session()->flash('errorMessage', __('cart.invalid_operation'));
            return to_route('products.index');
        }

        $addresses = Auth::user()->addresses;

        return view('orders.create', compact('addresses'));
    }

    public function confirm(AddressRequest $request)
    {
        session()->forget('errorMessages');

        foreach (session('cart') as $cartProduct) {
            $product = Product::find($cartProduct['id']);

            if ($product->stock == 0) {
                session()->forget('cart.' . $product->id);
                session()->push('errorMessages', $product->name . __('order.out_of_stock'));
            } elseif ($product->stock < $cartProduct['quantity']) {
                session()->put('cart.' . $product->id . '.quantity', $product->stock);
                session()->push('errorMessages', $product->name . __('order.stock_short'));
            }
        }

        if ($request->address == 1) {
            $address = Address::find($request->address_id);

            $addAddress = [
                'address' => $request->address,
                'name' => $address->name,
                'postal_code' => $address->postal_code,
                'pref_id' => $address->pref_id,
                'address1' => $address->address1,
                'address2' => $address->address2,
                'phone_number' => $address->phone_number,
            ];
        }

        if ($request->address == 2) {
            $addAddress = [
                'address' => $request->address,
                'name' => $request->name,
                'postal_code' => $request->postal_code,
                'pref_id' => $request->pref_id,
                'address1' => $request->address1,
                'address2' => $request->address2,
                'phone_number' => $request->phone_number,
            ];
        }

        session()->put('address', $addAddress);

        return view('orders.confirm');
    }

    public function store(Request $request)
    {
        $order = new Order;

        $order->user_id = Auth::id();
        $order->name = session('address')['name'];
        $order->postal_code = session('address')['postal_code'];
        $order->pref_id = session('address')['pref_id'];
        $order->address1 = session('address')['address1'];
        $order->address2 = session('address')['address2'];
        $order->phone_number = session('address')['phone_number'];
        $order->status = config('shipping_fee')[session('address')['pref_id']];
        $order->save();
        
        if (session('address')['address'] == 2) {
            Address::create([
                'user_id' => Auth::id(),
                'name' => $order->name,
                'postal_code' => $order->postal_code,
                'pref_id' => $order->pref_id,
                'address1' => $order->address1,
                'address2' => $order->address2,
                'phone_number' => $order->phone_number,
            ]);
        }

        foreach (session('cart') as $cartProduct) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $cartProduct['id'],
                'price' => $cartProduct['price'],
                'quantity' => $cartProduct['quantity'],
                'tax_rate' => config('app')['tax_rate'],
            ]);

            $product = Product::find($cartProduct['id']);

            $product->stock -= $cartProduct['quantity'];
            $product->save();
        }

        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        Charge::create(array(
            'amount' => Calculator::orderSum($order),
            'currency' => 'jpy',
            'source'=> $request->stripeToken,
        ));

        session()->forget('cart');
        session()->forget('address');

        session()->flash('successMessage', __('order.complete'));

        return to_route('products.index');
    }

    public function show($id)
    {
        $order = Order::find($id);

        return view('orders.show', compact('order'));
    }
}
