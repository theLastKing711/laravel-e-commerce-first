<?php

namespace App\Enum;

use App\Trait\EnumHelper;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    description: '[1 => Pending, 2 => Accept, 3 => DriverAccept, 4 => Rejected, 5 => Completed]',
    type: 'integer'
)]
enum OrderStatus: int
{
    case Pending = 1;
    case Accepted = 2;
    case DriverAccept = 3;
    case OnTheWay = 4;
    case Rejected = 5;
    case Completed = 6;

    use EnumHelper;
}
