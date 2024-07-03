<?php

namespace App\Enum;

use App\Trait\EnumHelper;

enum OrderStatus: int
{
    case Pending = 1;
    case Accept = 2;
    case DriverAccept = 3;
    case OnTheWay = 4;
    case Rejected = 5;
    case Completed = 6;

    use EnumHelper;
}
