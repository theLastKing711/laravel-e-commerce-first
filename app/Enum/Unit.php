<?php

namespace App\Enum;

use App\Trait\EnumHelper;

use OpenApi\Attributes as OAT;

#[OAT\Schema(
    description: '[1 => Kg, 2 => K, 3 => L, 4 => Ml, 5 => Stock]',
    type: 'integer'
)]
enum Unit: int
{
    case Kg = 1;
    case K = 2;
    case L = 3;
    case Ml = 4;
    case Stock = 5;

    use EnumHelper;
}
