<?php

namespace App\Enums;

enum ProductStatus: int
{
    case Active = 1;
    case Inactive = 9;

    public function label(): string
    {
        return match($this)
        {
            ProductStatus::Active => '販売中',
            ProductStatus::Inactive => '販売停止中',
        };
    }
}