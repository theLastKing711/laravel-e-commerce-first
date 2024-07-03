<?php

namespace App\Enum;

use App\Trait\EnumHelper;

enum Unit: int
{
    case Kg = 1;
    case K = 2;
    case L = 3;
    case Ml = 4;
    case Stock = 5;

    use EnumHelper;
}
