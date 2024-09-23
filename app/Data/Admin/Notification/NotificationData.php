<?php

namespace App\Data\Admin\Notification;

use App\Data\Shared\Swagger\Property\DateProperty;
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
        #[OAT\Property()]
        public string $id,
        #[OAT\Property()]
        public string $name,
        #[OAT\Property()]
        public string $body,
        #[OAT\Property()]
        public int $order_id,
        #[OAT\Property()]
        public OrderStatus $order_status,
        #[OAT\Property()]
        public NotificationType $type,
        #[DateProperty]
        public string $created_at,
    ) {
    }

}
