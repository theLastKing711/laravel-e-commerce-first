<?php

namespace App\Notifications;

use App\Broadcasting\Messages\OrderAcceptedWhatsAppMessage;
use App\Broadcasting\Messages\WhatsAppMessage;
use App\Broadcasting\WhatsAppChannel;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderAccepted extends Notification
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [WhatsAppChannel::class];
    }

    /**
     * Get the Whats app  representation of the notification.
     */
    public function toWhatsApp(object $notifiable): WhatsAppMessage
    {

        return (new WhatsAppMessage())
            ->content("order with id {$this->order->id} has been accepted");
    }
}
