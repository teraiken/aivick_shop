<?php

namespace App\Helpers;

use App\Models\Order;

class Calculator
{
  public static function arraySum(array $array): int
  {
    $total = 0;

    foreach ($array as $value) {
      $total += $value['price'] * $value['quantity'];
    }

    return $total;
  }

  public static function orderSum(Order $order): int
  {
    $subTotal = 0;

    $orderDetails = $order->orderDetails;

    foreach ($orderDetails as $orderDetail) {
      $subTotal += $orderDetail->price * $orderDetail->quantity * ($orderDetail->tax_rate + 100) / 100;
    }

    $total = floor($subTotal) + $order->shipping_fee;

    return $total;
  }
}
