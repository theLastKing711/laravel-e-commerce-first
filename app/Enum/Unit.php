<?php

namespace App\Enum;

enum Unit: int
{
    case Kg = 1;
    case K = 2;
    case L = 3;
    case Ml = 4;
    case Stock = 5;

    public static function asValuesArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
