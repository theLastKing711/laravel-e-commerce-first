<?php

namespace App\Enum;

use App\Trait\EnumHelper;

enum AccountRegistrationStep: int
{
    case Created = 0;
    case NeedInformation = 1;
    case NeedLocation = 2;
    case Verified = 3;

    use EnumHelper;
}
