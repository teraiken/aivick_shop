<?php

namespace App\Helpers;

use App\Models\Order;

class Formatter
{
  /**
   * 注文の件名を表示する。
   *
   * @param Order $order
   * @return string
   */
  public static function subject(Order $order): string
  {
    $orderDetails = $order->orderDetails;

    $count = count($orderDetails);

    $firstProductNameAndQuantity = $orderDetails[0]->product->name . $orderDetails[0]->quantity . '個';

    if ($count === 1) {
      $subject = $firstProductNameAndQuantity;
    } else {
      $subject = $firstProductNameAndQuantity . '外' . $count - 1 . '点';
    }

    return $subject;
  }
}
