<?php

namespace App\Http\Controllers\Store;

use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Data\Store\Order\PathParameters\OrderIdPathParameterData;
use App\Enum\OrderStatus;
use App\Events\TestEvent;
use App\Events\User\OrderStatusAccepted;
use App\Events\User\OrderStatusRejected;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderAccepted;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;

#[
    OAT\PathItem(
        path: '/store/orders/{id}/accept',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/storeOrderIdPathParameter',
            ),
        ],
    ),
    OAT\PathItem(
        path: '/store/orders/{id}/reject',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/storeOrderIdPathParameter',
            ),
        ],
    ),
]
class OrderController extends Controller
{
    #[OAT\Patch(path: '/store/orders/{id}/accept', tags: ['storeOrders'], )]
    #[SuccessNoContentResponse('Order Status Changed Successfully')]
    public function accept(OrderIdPathParameterData $request): bool
    {
        Log::info('accessing Store OrderController with id', ['id' => $request->id]);

        $order = Order::with('user')
            ->first($request->id);

        $order->update([
            'status' => OrderStatus::Accepted->value,
        ]);

        TestEvent::dispatch($order);

        Log::info('order value {order}', ['order' => $order]);

        $order_user = $order->user;

        $order_user->notify(new OrderAccepted($order));

        //        OrderStatusAccepted::dispatch($order);

        return true;

    }

    #[OAT\Patch(path: '/store/orders/{id}/reject', tags: ['storeOrders'], )]
    #[SuccessNoContentResponse('Order Status Changed Successfully')]
    public function reject(OrderIdPathParameterData $request)
    {

        Log::info('accessing Store OrderController with id', ['id' => $request->id]);

        $order = Order::find($request->id);

        $order->update([
            'status' => OrderStatus::Rejected->value,
        ]);

        TestEvent::dispatch($order);
        Log::info('order value {order}', ['order' => $order]);

        //        OrderStatusRejected::dispatch($order);

        return true;

    }
}
