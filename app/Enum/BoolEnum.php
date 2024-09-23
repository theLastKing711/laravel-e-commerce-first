<?php

namespace App\Enum;
use App\Trait\EnumHelper;

enum BoolEnum: int
{
    case Zero = 0;
    case One = 1;

    use EnumHelper;
}

