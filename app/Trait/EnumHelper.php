<?php

namespace App\Trait;

trait EnumHelper
{
    public static function asValuesArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function asKeysArray(): array
    {
        return array_column(self::cases(), 'name');
    }
}
