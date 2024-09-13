<?php

namespace App\Enum;

use App\Enum\Auth\RolesEnum;
use App\Trait\EnumHelper;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    description: '[0 => User, 1 => Driver, 2 => Store, 3 => All]',
    type: 'integer'
)
]
enum NotificationType: int
{
    case User = 0;
    case Driver = 1;
    case Store = 2;
    case All = 3;

    use EnumHelper;

    public function getUserRoleAsString(): string
    {
        return match ($this) {
            self::User => RolesEnum::USER->value,
            self::Driver => RolesEnum::DRIVER->value,
            self::Store => RolesEnum::STORE->value,
            self::All => 'all'
        };
    }
}
