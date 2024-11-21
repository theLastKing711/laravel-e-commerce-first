<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Notification\CreateNotificationData;
use App\Data\Admin\Notification\NotificationData;
use App\Data\Admin\Notification\PathParameters\NotificationIdPathParameterData;
use App\Data\Admin\Notification\QueryParameters\NotificationTypeQueryParameterData;
use App\Data\Shared\Swagger\Parameter\QueryParameter\QueryParameter;
use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessListResponse;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Enum\NotificationType;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;

#[
    OAT\PathItem(
        path: '/admin/notifications/{id}',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/adminNotificationIdPathParameter',
            ),
        ],
    ),
]
class NotificationController extends Controller
{
    /**
     * Get All Notifications
     */
    #[OAT\Get(path: '/admin/notifications', tags: ['notifications'])]
    #[QueryParameter('notification_type', NotificationType::class)]
    #[SuccessListResponse(NotificationData::class, 'The Notifications were successfully fetched')]
    public function index(NotificationTypeQueryParameterData $query_param)
    {
        Log::info($query_param);

        Log::info('accessing NotificationController index method');

        $isNotificationForAllUserRoles = $query_param->notification_type === NotificationType::All;

        $notifications = Notification::select([
            'id',
            'name',
            'body',
            'order_id',
            'order_status',
            'type',
            'created_at',
        ])
            ->when(! $isNotificationForAllUserRoles, function ($query) use ($query_param) {
                $query->where('type', $query_param->notification_type->value);
            })
            ->get();

        return NotificationData::collect($notifications);
    }

    /**
     * Create a new Notification.
     */
    #[OAT\Post(path: '/admin/notifications', tags: ['notifications'])]
    #[JsonRequestBody(CreateNotificationData::class)]
    #[SuccessNoContentResponse('Notification created successfully')]
    public function store(
        CreateNotificationData $createNotificationData,
    ): NotificationData {

        Log::info('Accessing NotificationController store method');

        $notification = Notification::create([
            'name' => $createNotificationData->name,
            'body' => $createNotificationData->body,
            'order_id' => $createNotificationData->order_id,
            'order_status' => $createNotificationData->order_status,
            'type' => $createNotificationData->type,
        ]);

        $notificationUserRole = $createNotificationData->type->getUserRoleAsString();

        $notificationUserIds = User::role($notificationUserRole)->get();

        $notification->users()->attach($notificationUserIds);

        Log::info('Notification was created {notification}', ['notification' => $notification]);
    }

    /**
     * Remove the specified resource from storage.
     */
    #[OAT\Delete(path: '/admin/notifications/{id}', tags: ['notifications'])]
    #[SuccessNoContentResponse('The Notification was successfully deleted')]
    public function destroy(NotificationIdPathParameterData $request): bool
    {

        Log::info('Accessing NotificationController destroy method with id {id}', ['id', $request->id]);

        $notificationToDelete = Notification::find($request->id);

        $isNotificationDeleted = $notificationToDelete->delete();

        return $isNotificationDeleted;

    }
}
