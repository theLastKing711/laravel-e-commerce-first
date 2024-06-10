<?php

namespace App\Enum;

enum Gender: int
{
    case Female = 1;
    case Male = 2;

    public static function asValuesArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
