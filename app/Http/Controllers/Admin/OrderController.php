<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $maxRecords = 5;

        $search = $request->search;
        $query = Order::search($search);

        $orders = $query->orderBy('id', 'desc')->paginate($maxRecords);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);

        $orderDetails = $order->orderDetails;

        return view('admin.orders.show', compact('order', 'orderDetails'));
    }
}
