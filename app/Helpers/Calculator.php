<?php

namespace App\Helpers;

use App\Models\Order;

class Calculator
{
  /**
   * 配列の合計金額を算出する。
   *
   * @param array $array
   * @return integer
   */
  public static function arraySum(array $array): int
  {
    $total = 0;

    foreach ($array as $value) {
      $total += $value['price'] * $value['quantity'];
    }

    return $total;
  }

  /**
   * 注文の支払金額を算出する。
   *
   * @param Order $order
   * @return integer
   */
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
