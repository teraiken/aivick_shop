<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Stripe\Charge;
use App\Helpers\Calculator;
use App\Http\Requests\AddressRequest;
use App\Mail\OrderMail;
use App\Models\Area;
use App\Services\StripeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    private StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

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
        $card = $this->stripeService->getCard(Auth::user());

        return view('orders.create', compact('addresses', 'card'));
    }

    public function confirm(AddressRequest $request)
    {
        session()->forget('errorMessages');

        foreach (session('cart') as $cartProduct) {
            $product = Product::find($cartProduct['id']);

            if ($product->stock == 0) {
                session()->forget('cart.' . $product->id);
                session()->push('errorMessages', __('order.out_of_stock', ['productname' => $product->name]));
            } elseif ($product->stock < $cartProduct['quantity']) {
                session()->put('cart.' . $product->id . '.quantity', $product->stock);
                session()->push('errorMessages', __('order.stock_short', ['productname' => $product->name]));
            }
        }

        $addAddress = [
            'selectedAddress' => $request->selectedAddress,
            'name' => $request->name,
            'postal_code' => $request->postal_code,
            'pref_id' => $request->pref_id,
            'address1' => $request->address1,
            'address2' => $request->address2,
            'phone_number' => $request->phone_number,
        ];

        session()->put('address', $addAddress);

        $card = $this->stripeService->getCard(Auth::user());

        return view('orders.confirm', compact('card'));
    }

    public function store()
    {
        $order = new Order;

        DB::transaction(function () use ($order) {
            $order->user_id = Auth::id();
            $order->name = session('address')['name'];
            $order->postal_code = session('address')['postal_code'];
            $order->pref_id = session('address')['pref_id'];
            $order->address1 = session('address')['address1'];
            $order->address2 = session('address')['address2'];
            $order->phone_number = session('address')['phone_number'];
            $order->shipping_fee = Area::find(config('area')[session('address')['pref_id']])->currentShippingFee->fee;
            $order->save();

            if (session('address')['selectedAddress'] == 'newAddress') {
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

            Charge::create(array(
                'amount' => Calculator::orderSum($order),
                'currency' => 'jpy',
                'customer' => Auth::user()->stripe_id,
            ));
        });

        Mail::to(Auth::user())->send(new OrderMail($order));

        session()->forget('cart');
        session()->forget('address');

        session()->flash('successMessage', __('order.complete'));

        return to_route('products.index');
    }

    public function show($id)
    {
        $order = Order::whereUserId(Auth::id())->find($id);

        $orderDetails = $order->orderDetails;

        return view('orders.show', compact('order', 'orderDetails'));
    }
}
