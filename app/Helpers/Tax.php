<?php
 
namespace App\Helpers;
 
class Tax
{
    public static function add($price)
    {
        $tax = 10;
        
        return floor($price * ($tax + 100) / 100);
    }
}