<?php

use App\Helpers\Calculator;

$orderDetails = $order->orderDetails;

?>

この度はaivick_shopをご利用いただきまして､誠にありがとうございます｡<br>
注文内容は以下のﾍﾟｰｼﾞからﾒｰﾙｱﾄﾞﾚｽ･ﾊﾟｽﾜｰﾄﾞを入力しご確認ください｡<br>
<a href="http://localhost/orders/{{ $order->id }}">http://localhost/orders/{{ $order->id }}</a><br>
<br>
@foreach ($orderDetails as $key => $orderDetail)
商品{{ $key+1 }}:{{ $orderDetail->product->name }} (税込¥{{ number_format($orderDetail->price * ($orderDetail->tax_rate +
100) / 100) }}×{{ $orderDetail->quantity }})<br>
@endforeach
注文者:{{ $order->user->name }}様<br>
配送先:{{ $order->name }}様(〒{{ substr_replace($order->postal_code, '-', 3, 0) }} {{ config('pref')[$order->pref_id] }}{{
$order->address1 }}{{ $order->address2 }} Tel{{ $order->phone_number }})<br>
支払金額:¥{{ number_format(Calculator::orderSum($order)) }}(送料:¥{{ number_format($order->shipping_fee) }})<br>