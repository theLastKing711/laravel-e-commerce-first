<?php

namespace App\Data\Admin\Notification;

use App\Enum\NotificationType;
use App\Enum\OrderStatus;
use App\Models\Notification;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'adminNotification')]
class NotificationData extends Data
{
    public function __construct(
        #[
            OAT\Property(type: 'integer'),
        ]
        public string $id,
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
        #[OAT\Property(
            type: 'string',
            format: 'datetime',
            default: '2017-02-02 18:31:45',
            pattern: 'YYYY-MM-DD'
        )]
        public string $created_at,
    ) {
    }

}
