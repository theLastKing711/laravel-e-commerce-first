<?php

namespace App\Data\Admin\Notification;

use App\Enum\NotificationType;
use App\Enum\OrderStatus;
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

#[Oat\Schema(schema: 'adminCreateNotification')]
class CreateNotificationData extends Data
{
    public function __construct(
        #[
            OAT\Property(type: 'string'),
        ]
        public string $name,
        #[
            OAT\Property(type: 'string'),
        ]
        public string $body,
        #[
            OAT\Property(type: 'integer'),
        ]
        public int $order_id,
        #[
            OAT\Property(),
        ]
        public OrderStatus $order_status,
        #[
            OAT\Property(),
        ]
        public NotificationType $type,

    ) {
    }
}
