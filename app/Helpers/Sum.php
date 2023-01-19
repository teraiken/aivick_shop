<?php
 
namespace App\Helpers;
 
class Sum
{
  public static function array(array $array): int
  {
    $total = 0;
    
    foreach ($array as $value) {
      $total += $value['price'] * $value['quantity'];
    }
    
    return $total;
  }
}