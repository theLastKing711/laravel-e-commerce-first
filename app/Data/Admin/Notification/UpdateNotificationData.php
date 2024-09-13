<?php

namespace App\Data\Admin\Notification;

use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\After;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Digits;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\RequiredWithout;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'adminUpdateNotification')]
class UpdateNotificationData extends Data
{
    public function __construct(
        #[
            OAT\Property(type: 'string'),
        ]
        public string $name,
        #[
            OAT\Property(type: 'string'),
            Digits(6)
        ]
        public string $code,
        #[
            OAT\Property(type: 'string'),
            Min(5),
            Max(90),
            Numeric,
            RequiredWithout('value')
        ]
        public ?string $percent,
        #[
            OAT\Property(type: 'integer'),
            RequiredWithout('percent')
        ]
        public ?int $value,
        #[
            OAT\Property(
                type: 'string',
                format: 'datetime',
                default: '2024-08-02 18:31:45',
                pattern: 'YYYY-MM-DD'
            ),
            AfterOrEqual('- 5 minutes'),
            Date
        ]
        public string $start_at,
        #[
            OAT\Property(
                type: 'string',
                format: 'datetime',
                default: '2024-09-02 18:31:45',
                pattern: 'YYYY-MM-DD'
            ),
            After('start_at'),
            Date
        ]
        public string $end_at,
        #[OAT\Property(
            type: 'array',
            items: new OAT\Items(
                type: 'integer',
            )
        )]
        /** @var Collection<int, int> */
        public array $user_ids,
        #[OAT\Property(
            type: 'array',
            items: new OAT\Items(
                type: 'integer',
            )
        )]
        /** @var Collection<int, int> */
        public array $group_ids,
    ) {
    }
}
