<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Notification\CreateNotificationData;
use App\Data\Admin\Notification\NotificationData;
use App\Data\Admin\Notification\PathParameters\NotificationIdPathParameterData;
use App\Data\Admin\Notification\QueryParameters\NotificationTypeQueryParameterData;
use App\Enum\Auth\RolesEnum;
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
    private string $notificationRole = RolesEnum::USER->value;

    /**
     * Get All Notifications
     */
    #[OAT\Get(
        path: '/admin/notifications',
        tags: ['notifications'],
        parameters: [
            new OAT\QueryParameter(
                ref: '#/components/parameters/adminNotificationType',
            ),
        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'The Notification was successfully created',
                content: new OAT\JsonContent(
                    type: 'array',
                    items: new OAT\Items(
                        type: NotificationData::class
                    ),
                ),
            ),
        ],
    )]
    public function index(NotificationTypeQueryParameterData $query_param)
    {

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
    #[OAT\Post(
        path: '/admin/notifications',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(type: CreateNotificationData::class),
        ),
        tags: ['notifications'],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'Notification created successfully',
                content: new OAT\JsonContent(type: NotificationData::class),
            ),
        ],
    )]
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

        return NotificationData::from($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    #[OAT\Delete(
        path: '/admin/notifications/{id}',
        tags: ['notifications'],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'The Notification was successfully deleted',
            ),
        ],
    )]
    public function destroy(NotificationIdPathParameterData $request): bool
    {

        Log::info('Accessing NotificationController destroy method with id {id}', ['id', $request->id]);

        $notificationToDelete = Notification::find($request->id);

        $isNotificationDeleted = $notificationToDelete->delete();

        return $isNotificationDeleted;

    }
}