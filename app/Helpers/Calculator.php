<?php
 
namespace App\Helpers;
 
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
}