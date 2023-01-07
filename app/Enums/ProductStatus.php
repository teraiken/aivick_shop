<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ProductStatus extends Enum
{
    const ACTIVE = 1;
    const INACTIVE = 9;

    public static function getDescription($value): string
    {
        if ($value === self::ACTIVE) {
            return '販売中';
        }

        if ($value === self::INACTIVE) {
            return '販売停止中';
        }

        return parent::getDescription($value);
    }
}
