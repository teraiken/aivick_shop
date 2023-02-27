<?php

namespace App\Helpers;

class Tax
{
    /**
     * 税込価格を表示する。
     *
     * @param integer $price
     * @return integer
     */
    public static function add(int $price): int
    {
        return floor($price * (config('app')['tax_rate'] + 100) / 100);
    }
}
