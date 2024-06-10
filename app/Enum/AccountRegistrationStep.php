<?php

namespace App\Enum;

enum AccountRegistrationStep: int
{
    case Created = 0;
    case NeedInformation = 1;
    case NeedLocation = 2;
    case Verified = 3;

    public static function asValuesArray(): array
    {
        return array_column(self::cases(), 'value');
    }

}
