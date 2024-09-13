<?php

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('orders', function (User $user) {

    //    $order_user_id = Order::find($orderId)->user_id;

    return true;
});
