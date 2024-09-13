<?php

namespace App\Data\Admin\Notification\QueryParameters;

use App\Enum\NotificationType;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

class NotificationTypeQueryParameterData extends Data
{
    public function __construct(
        #[OAT\QueryParameter(
            parameter: 'adminNotificationType', //the name used in ref
            name: 'notification_type', // the name used in swagger ui, becomes the ref in case the parameter is missing
            schema: new OAT\Schema()
        )]
        public NotificationType $notification_type,
    ) {
    }
}
