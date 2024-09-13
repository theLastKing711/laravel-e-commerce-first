<?php

namespace App\Enum;

use App\Trait\EnumHelper;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    description: '[
     0 => Saturday,
     1 => Sunday,
     2 => Monday,
     3 => Tuesday,
     4 => Wednesday,
     5 => Thursday,
     6 => Friday
     ]',
    type: 'integer'
)]
enum Day: int
{
    case Saturday = 0;
    case Sunday = 1;
    case Monday = 2;
    case Tuesday = 3;
    case Wednesday = 4;
    case Thursday = 5;
    case Friday = 6;

    use EnumHelper;
}
