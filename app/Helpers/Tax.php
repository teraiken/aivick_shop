<?php
 
namespace App\Helpers;
 
class Tax
{
    public static function add(int $price): int
    {    
        return floor($price * (config('app')['tax_rate'] + 100) / 100);
    }
}