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
use App\Models\Pref;
use App\Services\StripeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OrderController extends Controller
{
    private StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * リソースの一覧を表示する。
     *
     * @return View
     */
    public function index(): View
    {
        $maxRecords = 5;

        $orders = Order::whereUserId(Auth::id())->orderBy('id', 'desc')->paginate($maxRecords);

        return view('orders.index', compact('orders'));
    }

    /**
     * 新規リソースの作成フォームを表示する。
     *
     * @return RedirectResponse|View
     */
    public function create(): RedirectResponse|View
    {
        if (empty(session('cart'))) {
            session()->flash('errorMessage', __('cart.invalid_operation'));
            return to_route('products.index');
        }

        $addresses = Auth::user()->addresses;
        $prefs = Pref::all();
        $card = $this->stripeService->getCard(Auth::user());

        return view('orders.create', compact('addresses', 'prefs', 'card'));
    }

    /**
     * 新規リソースの確認画面を表示する。
     *
     * @param AddressRequest $request
     * @return View
     */
    public function confirm(AddressRequest $request): View
    {
        session()->forget('errorMessages');

        $this->checkStock();

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

        $pref = Pref::find($request->pref_id);
        $card = $this->stripeService->getCard(Auth::user());

        return view('orders.confirm', compact('pref', 'card'));
    }

    /**
     * 新規リソースが在庫不足の場合のみ表示する。
     *
     * @return View
     */
    public function short(): View
    {
        $card = $this->stripeService->getCard(Auth::user());

        return view('orders.confirm', compact('card'));
    }

    /**
     * 新しく作成されたリソースをストレージに格納する。
     *
     * @return RedirectResponse
     */
    public function store(): RedirectResponse
    {
        session()->forget('errorMessages');

        if ($this->checkStock()) {
            return to_route('orders.short');
        }

        $order = new Order;

        DB::transaction(function () use ($order) {
            $order->user_id = Auth::id();
            $order->name = session('address')['name'];
            $order->postal_code = session('address')['postal_code'];
            $order->pref_id = session('address')['pref_id'];
            $order->address1 = session('address')['address1'];
            $order->address2 = session('address')['address2'];
            $order->phone_number = session('address')['phone_number'];
            $order->shipping_fee = Pref::find(session('address')['pref_id'])->area->currentShippingFee->fee;
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

                $product = Product::lockForUpdate()->find($cartProduct['id']);

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

    /**
     * 指定されたリソースを表示する。
     *
     * @param [type] $id
     * @return View
     */
    public function show($id): View
    {
        $order = Order::whereUserId(Auth::id())->find($id);

        $orderDetails = $order->orderDetails;

        return view('orders.show', compact('order', 'orderDetails'));
    }

    /**
     * 在庫を確認する。
     *
     * @return boolean
     */
    private function checkStock(): bool
    {
        $shortFlag = false;

        foreach (session('cart') as $cartProduct) {
            $product = Product::onSale()->find($cartProduct['id']);

            if (is_null($product)) {
                session()->forget('cart.' . $cartProduct['id']);
                session()->push('errorMessages', __('order.end_of_sale', ['productname' => $cartProduct['name']]));
                $shortFlag = true;
            } elseif ($product->stock === 0) {
                session()->forget('cart.' . $product->id);
                session()->push('errorMessages', __('order.out_of_stock', ['productname' => $product->name]));
                $shortFlag = true;
            } elseif ($product->stock < $cartProduct['quantity']) {
                session()->put('cart.' . $product->id . '.quantity', $product->stock);
                session()->push('errorMessages', __('order.stock_short', ['productname' => $product->name]));
                $shortFlag = true;
            }
        }

        return $shortFlag;
    }
}
