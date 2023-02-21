<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * リソースの一覧を表示する。
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $maxRecords = 5;

        $search = $request->search;
        $query = Order::search($search);

        $orders = $query->orderBy('id', 'desc')->paginate($maxRecords);

        return view('admin.orders.index', compact('orders', 'search'));
    }

    /**
     * 指定されたリソースを表示する。
     *
     * @param [type] $id
     * @return View
     */
    public function show($id): View
    {
        $order = Order::find($id);

        $orderDetails = $order->orderDetails;

        return view('admin.orders.show', compact('order', 'orderDetails'));
    }
}
