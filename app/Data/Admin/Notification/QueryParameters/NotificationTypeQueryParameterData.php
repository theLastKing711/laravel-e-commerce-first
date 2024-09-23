<?php

namespace App\Data\Admin\Notification\QueryParameters;

use App\Enum\NotificationType;
use Spatie\LaravelData\Data;

class NotificationTypeQueryParameterData extends Data
{
    public function __construct(
        public NotificationType $notification_type,
    ) {
    }
}
