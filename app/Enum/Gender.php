<?php

namespace App\Enum;

use App\Trait\EnumHelper;
use OpenApi\Attributes as OAT;

#[OAT\Schema(type: 'integer', description: '[0 => Male, 1 => Female]')]
enum Gender: int
{
    case Male = 0;
    case Female = 1;

    use EnumHelper;
}
