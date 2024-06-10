<?php

namespace App\Enum;

enum OrderStatus: int
{
    case Pending = 1;
    case Accept = 2;
    case DriverAccept = 3;
    case OnTheWay = 4;
    case Rejected = 5;
    case Completed= 6;

    public static function asValuesArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
